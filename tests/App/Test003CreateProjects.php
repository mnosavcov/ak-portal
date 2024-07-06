<?php

namespace Tests\App;

use App\Models\Project;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Str;
use Tests\TestCase;

class Test003CreateProjects extends TestCase
{
    /**
     * @dataProvider \Tests\DataSets\DataProjects::projectDefault()
     */
    public function test_new_project_no_logged($input): void
    {
        $response = $this->post('/projekty',
            $input
        );

        $response->assertRedirect('/login');
    }

    /**
     * @dataProvider \Tests\DataSets\DataProjects::projectUsersMatrix()
     */
    public function test_create_project_all_users($input): void
    {
        $response = $this->post('/login', [
            'email' => $input['emailuser'],
            'password' => 'password',
        ]);

        $user = auth()->user();

        if ($user->isSuperadmin()) {
            $response->assertRedirect('/admin');
        } else {
            $response->assertRedirect(RouteServiceProvider::HOME);
        }

        if ($input['project']['accountType'] === 'real-estate-broker') {
            $accountType = 'real_estate_broker';
        } else {
            $accountType = $input['project']['accountType'];
        }

        $response = $this->post('/projekty',
            ['data' => json_encode(['data' => $input['project']])]
        );

        if (!$user->{$accountType}) {
            $response->assertRedirect(route('homepage'));
        } elseif (!in_array($input['project']['status'], ['draft', 'send'])) {
            $response->assertRedirect(route('homepage'));
        } else {
            $response->assertStatus(200);
        }
    }

    public function test_data_in_projects(): void
    {
        $this->assertSame(48, Project::count());

        foreach (Project::All() as $project) {
            if ($project->user_account_type === 'real-estate-broker') {
                $accountType = 'real_estate_broker';
            } else {
                $accountType = $project->user_account_type;
            }

            $user = User::find($project->user_id);
            $this->assertTrue((bool)$user->{$accountType});
            $this->assertNull($project->subcategory_id);
            $this->assertNull($project->end_date);
            $this->assertStringStartsWith('Projekt ', $project->title);
            $this->assertEquals(33, strlen($project->title));
            $this->assertStringStartsWith('<p>', $project->description);
            $this->assertStringEndsWith('</p>', $project->description);
            $this->assertEquals(38, strlen($project->description));
            $this->assertNull($project->about);
            $this->assertNull($project->short_info);
            $this->assertNull($project->actual_state);
            $this->assertNull($project->user_reminder);
            $this->assertNull($project->price);
            $this->assertNull($project->minimum_principal);
            $this->assertEquals('nabidka-plochy-pro-vystavbu-fve', $project->subject_offer);
            $this->assertEquals('pozemni-fve', $project->location_offer);
            $this->assertEquals('ceska_republika', $project->country);
            $this->assertEquals(Str::slug($project->title), $project->page_url);
            $this->assertNull($project->page_title);
            $this->assertNull($project->page_description);
            $this->assertNull($project->representation_type);
//            $this->assertNull($project->representation_end_date);
//            $this->assertNull($project->representation_indefinitely_date);
//            $this->assertNull($project->representation_may_be_cancelled);
            $this->assertEquals(0, $project->exclusive_contract);
            $this->assertEquals(1, $project->details_on_request);
        }
    }

    /**
     * @dataProvider \Tests\DataSets\DataUsers::users()
     */
    public function test_data_projects_update($input): void
    {
        $response = $this->post('/login', [
            'email' => $input['kontakt']['email'],
            'password' => 'password',
        ]);

        $user = auth()->user();

        foreach (Project::all() as $project) {
            $response = $this->post(route('projects.update', ['project' => $project->id]),
                ['data' => json_encode(['data' => [
                    'title' => 'Projekt ' . Str::random(25),
                    'type' => $project->type,
                    'status' => $project->status,
                    'description' => $project->description,
                    'subjectOffer' => $project->subject_offer,
                    'locationOffer' => $project->location_offer,
                    'country' => $project->country,
                    'uuid' => Str::uuid(),
                    'files' => [],
                    'representation' => [
                        'selected' => 'non-exclusive',
                        'indefinitelyDate' => true,
                        'endDate' => null,
                        'mayBeCancelled' => 'yes',
                    ]
                ]])]
            );

            if ($project->user_id !== $user->id) {
                $this->assertEquals(
                    json_encode([
                        'status' => 'error',
                        'redirect' => route('homepage'),
                    ]), $response->getContent()
                );

                $this->assertEquals(Str::slug(Project::find($project->id)->title), Project::find($project->id)->page_url);
            } elseif (!in_array($project->status, Project::STATUS_DRAFT)) {
                $this->assertEquals(
                    json_encode([
                        'status' => 'error',
                        'redirect' => route('homepage'),
                    ]), $response->getContent()
                );

                $this->assertEquals(Str::slug(Project::find($project->id)->title), Project::find($project->id)->page_url);
            } else {
                $this->assertNotEquals(Str::slug(Project::find($project->id)->title), Project::find($project->id)->page_url);
                Project::find($project->id)->update(['page_url' => Str::slug(Project::find($project->id)->title)]);
            }
        }
    }
}
