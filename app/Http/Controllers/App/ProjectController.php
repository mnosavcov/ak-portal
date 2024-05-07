<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        return view('app.projects.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create($accountType)
    {
        $data = [
            'route' => route('projects.create', ['accountType' => $accountType]),
            'accountType' => $accountType,
            'status' => 'draft',
            'subjectOffers' => ProjectService::SUBJECT_OFFERS,
            'subjectOffer' => null,
            'locationOffers' => ProjectService::LOCATION_OFFERS,
            'locationOffer' => null,
            'title' => '',
            'description' => '',
            'country' => '',
            'type' => null,
            'types' => [
                [
                    'value' => 'fixed_price',
                    'text' => 'Cenu stanovíte vy (prodávající)',
                    'description' => 'V projektu nastavíte fixní cenu, kterou chcete za projekt obdržet. Jakmile ji některý z investorů nabídne, dochází k ukončení projektu.',
                ],
                [
                    'value' => 'offer_the_price',
                    'text' => 'Cenu stanoví zájemce o projekt (investor)',
                    'description' => 'Zájemci o projekt předkládají po vámi určenou dobu své nabídky, jejichž výše není veřejná. Po skončení sběru nabídek vyberete vítěze. Můžete nastavit minimální částku, za kterou jste ochotni projekt prodat.',
                ],
//                [
//                    'value' => 'auction',
//                    'text' => 'Prodej formou aukce',
//                    'description' => 'Nastavíte délku trvání aukce, vyvolávací částku a minimální příhoz. Zájemci spolu soutěží. Vítězem bude ten, kdo nabídne nejvíce.',
//                ],
            ],
            'representation' => [
                'selected' => null,
                'endDate' => '',
                'indefinitelyDate' => false,
                'mayBeCancelled' => null,
            ],
            'representationOptions' => [
                [
                    'value' => 'exclusive',
                    'text' => 'Výhradní zastoupení',
                    'description' => 'Klienta zastupujete jen vy. Za zveřejnění projektu nic neplatíte. Platíte jen za úspěšné zprostředkování prodeje ve výši, na které se dohodneme před zveřejněním projektu.',
                ],
                [
                    'value' => 'non-exclusive',
                    'text' => 'Nevýhradní zastoupení',
                    'description' => 'Nemáte exkluzivní právo na zprostředkování prodeje projektu. Za zveřejnění projektu platíte dle našeho ceníku. Zaplatíte za úspěšné zprostředkování prodeje naším portále. Od této částky bude odečten poplatek za zveřejnění projektu.',
                ],
            ],
            'files' => [],
        ];

        return view('app.projects.create', ['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = json_decode($request->post('data'));
        $insert = [
            'user_account_type' => $data->data->accountType,
            'type' => $data->data->type,
            'status' => $data->data->status,
            'title' => $data->data->title,
            'description' => $data->data->description,
            'subject_offer' => $data->data->subjectOffer,
            'location_offer' => $data->data->locationOffer,
            'country' => $data->data->country
        ];

        if ($data->data->accountType === 'real-estate-broker') {
            $insert['representation_type'] = $data->data->representation->selected;
            if (!$data->data->representation->indefinitelyDate) {
                $insert['representation_end_date'] = $data->data->representation->endDate;
            }
            $insert['representation_indefinitely_date'] = (bool)$data->data->representation->indefinitelyDate;
            $insert['representation_may_be_cancelled'] = (bool)$data->data->representation->mayBeCancelled === 'yes';
        }

        $project = Project::create($insert);

        foreach ($request->file('files') as $file) {
            $path = $file->store(auth()->id() . '/' . $project->id);
            $projectFile = new ProjectFile([
                'filepath' => $path,
                'filename' => $file->getClientOriginalName(),
                'order' => 0,
                'public' => false,
            ]);

            $project->files()->save($projectFile);
        }

        return response()->json([
            'status' => 'success',
            'redirect' => route('profile.overview', ['account' => $project->user_account_type]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contact $kontakty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $kontakty)
    {
        //
    }

    public function saveOrder(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contact $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }
}
