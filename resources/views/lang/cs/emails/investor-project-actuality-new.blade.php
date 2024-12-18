<x-email-layout :unsubscribeUrl="$unsubscribeUrl ?? null">
Dobrý den,<br>
<br>
u projektu <a href="{{ $project->url_detail }}">{{ $project->title }}</a>, který sledujete, byla zveřejněna nová aktualita.<br>
<br>
Pro pro zobrazení aktuality se přihlaste do svého účtu investora. Naleznete ji u projektu v záložce Aktuality.<br>
<br>
S pozdravem,<br>
Tým PVtrusted.cz
</x-email-layout>
