<x-email-layout>
Dobrý den,<br>
<br>
na portálu PVtrusted.cz byl zveřejněn nový projekt <a href="{{ $project->url_detail }}">{{ $project->title }}</a>.<br>
<br>
<strong>Úvodní informace:</strong> {!! $project->short !!}<br>
<br>
<strong>Typ prodeje projektu:</strong> {{ $project->type_text }}<br>
<br>
<strong>Kategorie:</strong> {{ $project->category_text }}<br>
<br>
<strong>Termín ukončení sběru nabídek:</strong> {{ $project->end_date_text_normal }}<br>
<br>
<strong>Projekt v bodech:</strong> {{ $project->tag_list }}<br>
<br>
Pro zobrazení plného znění projektu se přihlaste do svého účtu investora.<br>
<br>
S pozdravem,<br>
Tým PVtrusted.cz
</x-email-layout>
