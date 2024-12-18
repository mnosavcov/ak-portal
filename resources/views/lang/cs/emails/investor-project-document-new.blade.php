<x-email-layout :unsubscribeUrl="$unsubscribeUrl ?? null">
Dobrý den,<br>
<br>
u projektu <a href="{{ $project->url_detail }}">{{ $project->title }}</a>, který sledujete, byla zveřejněna nová příloha s názvem "{{ $document->filename }}", kterou u projektu naleznete v adresáři "{{ $document->filefolder }}".<br>
<br>
Pro pro stažení přílohy se přihlaste do svého účtu investora. Naleznete ji u projektu v záložce Dokumentace.<br>
<br>
S pozdravem,<br>
Tým PVtrusted.cz
</x-email-layout>
