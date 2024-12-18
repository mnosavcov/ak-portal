<x-email-layout :unsubscribeUrl="$unsubscribeUrl ?? null">
Dobrý den,<br>
<br>
u projektu <a href="{{ $project->url_detail }}">{{ $project->title }}</a> na PVtrusted.cz, který sledujete, byl zveřejněn nový komentář od uživatele "{{ $comment->user_name }}".<br>
<br>
Pro zobrazení komentáře se přihlaste do svého účtu investora. Naleznete ho u projektu v záložce Otázky a odpovědi.<br>
<br>
S pozdravem,<br>
Tým PVtrusted.cz
</x-email-layout>
