<x-email-layout :unsubscribeUrl="$unsubscribeUrl ?? null">
Dobrý den,<br>
<br>
projekt <a href="{{ $project->url_detail }}">{{ $project->title }}</a> na PVtrusted.cz, který sledujete, byl ukončen. <br>
<br>
To znamená, že došlo buď k úspěšnému propojení nabízejícího a investora, nebo se žádný zájemce nenašel a nabízející se rozhodl projekt dál neinzerovat.<br>
<br>
S pozdravem,<br>
Tým PVtrusted.cz
</x-email-layout>
