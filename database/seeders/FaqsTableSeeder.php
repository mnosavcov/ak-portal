<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FaqsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faqs')->insert([
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Jak ověřujete projekty?',
                'odpoved' => 'U každého projektu provádíme důslednou rešerši informací, které nám nabízející poskytnou. Finální znění textových informací v projektu a popis dokumentace provádíme my v rámci procesu přípravy projektu před zveřejněním. Mimo popis projektu ověřujeme i totožnost nabízejícího, resp. vlastníka projektu. Dále také oprávněnost a způsobilost projekt nabízet. Detailní informace o tom, jak to funguje naleznete v našich <a href="/vseobecne-obchodni-podminky" class="text-app-blue underline hover:no-underline">Všeobecných obchodních podmínkách</a>.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Proč ověřujete moji totožnost a oprávněnost zájmu na informace o projektech?',
                'odpoved' => 'Detailní informace o projektech považujeme za neveřejné. Jsou určeny jen pro kvalifikované investory – tedy pro subjekty, které prokážou, že jsou schopné investici potenciálně realizovat. Abychom mohli tento parametr posoudit, je třeba, aby se nám každý ztotožnil jako fyzická osoba. Smyslem tohoto postupu je omezení přístupu k citlivým informacím o projektech jen na nezbytně nutný okruh investorů.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Proč musím platit při podání nabídky jistotu?',
                'odpoved' => 'U každého projektu je po dohodě s nabízejícím nastavena výše jistoty, jejíž zaplacení na bankovní účet provozovatele portálu je podmínkou platnosti podané nabídky. Jistota se platí, aby se zamezilo falešným projevům zájmu o nákup projektu, které by mohly v krajním případě zmařit prodej nebo vlastníka poškodit.<br><br>
V případě neuzavření rezervační smlouvy z důvodů na straně zájemce, který o nákup projevil zájem, jistota propadá. Zájemcům, jejichž nabídka nebyla akceptována, kteří jistotu uhradili, je vrácena na bankovní účet, ze kterého byla poslána.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Je u vás jistota zaplacená zájemcem v bezpečí?',
                'odpoved' => 'Jako zprostředkovatel, v případě prodeje nemovitostí, vykonáváme vázanou činnost a podléháme zákonu č. 39/2020 Sb. Zákon o realitním zprostředkování a o změně souvisejících zákonů. V rámci zákonných povinností máme mimo jiné sjednané a zaplacené pojištění odpovědnosti za újmu způsobenou realitním zprostředkovatelem.<br><br>
Jistoty jsou zasílány na bankovní účet pro ně zvlášť určený a po dobu nároku na jejich držení jej neopouští.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Co znamená vyšší stupeň ověření investorů?',
                'odpoved' => 'Jedná se o nastavení projektu, kdy nabízející může udělovat přístup k neveřejným informacím o projektu jen investorům, které mu provozovatel identifikuje, dle svého uvážení a za svých podmínek. Můžete například požadovat podepsání NDA. V případě, že se nabízející rozhodne neudělit vám plný přístup k projektu, nemůžete učinit nabídku. Proti jeho rozhodnutí se zároveň nelze odvolat.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Co když se zavážu ke koupi projektu, u kterého vlastník zamlčel podstatné vady?',
                'odpoved' => 'Než s vlastníkem uzavřete kupní smlouvu či smlouvy o smlouvě budoucí kupní, musíte si ověřit, zda jsou všechna tvrzení a parametry projektu v souladu s tím, co vlastník u svého projektu deklaruje. Kdyby došlo k hrubému zamlčení skutečností, které by mohly způsobit, že nebude projekt realizovatelný, máte nárok Kupní smlouvu či Smlouvy o smlouvě budoucí kupní neuzavřít. Zároveň můžete požádat o vrácení zaplacené jistoty nebo rezervačního poplatku. Pokud byste již uzavřeli Kupní smlouvu a zaplatili kupní cenu, můžete od smlouvy odstoupit a požadovat od vlastníka vrácení kupní ceny.',
            ],
            [
                'pro_koho' => 'Pro investory',
                'otazka' => 'Jak se od sebe liší jednotlivé typy prodeje projektů?',
                'odpoved' => 'Na našem portálu se projekty prodávají třemi způsoby: „Cenu navrhuje investor“, „Cenu navrhuje nabízející“ a „Aukce“. Princip jednotlivých typů prodeje je popsán v oddíle „XIII. Nastavení projektu“ ve <a href="/vseobecne-obchodni-podminky" class="text-app-blue underline hover:no-underline">Všeobecných obchodních podmínkách</a>.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Jaké projekty mohu na portálu nabízet?',
                'odpoved' => 'Na našem portálu se nabízejí projekty či jejich části převážně z oblasti výstavby a provozu obnovitelných zdrojů energie v různých stupních rozpracovanosti.<br><br>
Z hlediska zmíněné rozpracovanosti považujeme za nutné minimum, abychom investiční příležitost mohli prohlásit za zveřejnitelný projekt, alespoň vlastnictví platného návrhu smlouvy o připojení výrobny do sítě distributora. Zveřejnění projektu, kdy by byl nabízen pouze pozemek pro potenciální výrobnu, bez znalosti možnosti připojení, není pravděpodobné.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Proč ověřujete mou totožnost?',
                'odpoved' => 'Musíme si být jisti, že jste oprávněni projekt nabízet, což není možné bez plné identifikace vás jako fyzické osoby.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Mám na zveřejnění projektu nárok?',
                'odpoved' => 'Každý projekt posuzujeme individuálně. Vyhrazujeme si právo odmítnout jakýkoliv projekt. A ve většině případů se dozvíte i důvod.<br><br>
Realizovatelnost projektu není jedinou podmínkou. Projekt můžeme odmítnout zveřejnit i v případě, kdy má nabízející zjevně nerealistické představy například o minimální nabídkové ceně – tedy v takové výši, která je příliš vysoko nad námi vnímaným tržním průměrem.<br><br>
Zveřejnění projektu můžeme odmítnout i při nedostatečné součinnosti nabízejícího při předávání námi požadovaných informací, nebo pokud záměr vyhodnotíme jako příliš rizikový.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Jak se u vás projekty prodávají?',
                'odpoved' => 'Na našem portálu se projekty prodávají třemi způsoby: „Cenu navrhuje investor“, „Cenu navrhuje nabízející“ a „Aukce“. Princip jednotlivých typů prodeje je popsán v oddíle „XIII. Nastavení projektu“ ve <a href="/vseobecne-obchodni-podminky" class="text-app-blue underline hover:no-underline">Všeobecných obchodních podmínkách</a>.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Kolik zveřejnění projektu stojí?',
                'odpoved' => 'S každým vlastníkem projektu uzavíráme před započetím přípravy zveřejnění smlouvu o zprostředkování prodeje. Pokud je předmětem prodeje i nemovitost, tak smlouvu o realitním zprostředkování, se jako řídíme Zákonem č. 39/2020 Sb. Zákon o realitním zprostředkování a o změně souvisejících zákonů.<br><br>
Pokud s námi uzavřete smlouvu o zprostředkování prodeje (resp. smlouvu o realitním zprostředkování) s výhradním zastoupením, za zveřejnění projektu nic neplatíte. Náleží nám však provize, která je vyjádřena jako hodnota určená jako % z kupní ceny, kterou je povinen vlastník uhradit na základě obstarání příležitosti k uzavření kupní smlouvy nebo smlouvy o smlouvě budoucí kupní. Výši provize si sjednáme v rámci obchodního vyjednávání. Námi požadovaná hodnota bude odvislá o konkrétních specifikací projektu a předpokládané náročnosti zprostředkování prodeje.<br><br>
Pokud s námi uzavřete smlouvu o zprostředkování prodeje (resp. smlouvu o realitním zprostředkování) s nevýhradním zastoupením, musíte s námi před zveřejněním projektu uzavřít smlouvu poskytování služeb a zaplatit inzertní poplatek. I v tomto případě nám náleží provize.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Můžu si prodej projektu po zveřejnění rozmyslet?',
                'odpoved' => 'Možnost nepřistoupit k uzavření rezervační smlouvy je dostupná jen u typu prodeje „Cenu navrhuje investor“ a to jen v případě, že na tuto možnost upozorníte investory přímo v projektu. Tato možnost se volí zejména v případě, že s námi máte uzavřenou nevýhradní smlouvu o zprostředkování.<br><br>
U typů prodeje „Cenu navrhuje nabízející“ a „Aukce“ není možné bez sankcí od záměru odstoupit. Seznamte se s kompletními podmínkami ve <a href="/vseobecne-obchodni-podminky" class="text-app-blue underline hover:no-underline">Všeobecných obchodních podmínkách</a>.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'K čemu slouží vyšší stupeň ověření investorů?',
                'odpoved' => 'Je to nastavení, které si můžete vyžádat u provozovatele portálu, se kterým máte možnost schvalovat, nebo zamítat přístup k plnému znění projektu investorům. Provozovatel vám bude zobrazovat žádosti investorů o zpřístupnění neveřejných informací a zároveň vám je bude identifikovat.<br><br>
Tato možnost není dostupná, pokud s námi máte sjednanou smlouvu o zprostředkování v nevýhradním režimu.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Jakou minimální nabídkovou cenu mám zvolit?',
                'odpoved' => 'Minimální nabídkovou cenu musíte zvolit u dvou typů prodeje projektu. A to u “Cenu navrhuje investor” a “Aukce”. Minimální nabídková cena by měla odpovídat nejnižší možné ceně, za kterou jste ochotni projekt prodat. U obou typů prodeje, pokud bude více zájemců, můžete obdržet reálně výrazně více.<br><br>
U typu prodeje projektů, který nazýváme “Cenu navrhuje nabízející” volíte fixní nabídkovou cenu. To je cena, kterou jste ochotni akceptovat. A zároveň je to cena, se kterou budete plně spokojeni. Jakmile ji zájemce nabídne a složí jistotu (a je první v pořadí), má nárok na uzavření rezervační smlouvy.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Musím si u projektu, kde cenu navrhuje investor vybrat nabídku s nejvyšší částkou?',
                'odpoved' => 'Nemusíte. Můžete zohlednit i profil a důvěryhodnost zájemce.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Jak dlouho lze projekt nabízet?',
                'odpoved' => 'Projekty lze spustit na určito a na neurčito. Na určito lze spustit všechny typy prodeje projektů. V takovém případě v detailu projektu běží odpočet, který vyjadřuje lhůtu pro podání nabídek. Na neurčito lze spusti pouze typ prodeje projektu “Cenu stanovuje nabízející”. V takovém případě se čeká, dokud nějaký zájemce neakceptuje podmínky prodeje a učiní platnou nabídku.',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Do kdy musím akceptovat některou nabídek?',
                'odpoved' => 'V případě typů projektu “Cenu navrhuje nabízející” a “Aukce” dochází k akceptaci nabídky automaticky. V případě “Cenu navrhuje nabízející” podáním nabídky a zaplacením jistoty. U “Aukce” je akceptována nabídka se zaplacenou jistotou, která je podána s uplynutím pro podání nabídek jako poslední.<br><br>
U typu projektu “Cenu navrhuje investor” máte na akceptaci nabídky 14 dní od ukončení fáze sběru nabídek, pokud se s provozovatelem nedohodnete jinak (je to třeba uvést přímo v projektu).',
            ],
            [
                'pro_koho' => 'Pro nabízející',
                'otazka' => 'Co se děje po akceptaci nabídky?',
                'odpoved' => 'Po akceptaci vás a zájemce provozovatel vyzve k uzavření rezervační smlouvy. Zájemce následně musí složit rezervační poplatek, kterým stvrdí svůj zájem. Jakmile se obě strany, zájemce a vlastník, ujistí o splnění všech závazků, mohou přistoupit k uzavření kupní smlouvy či smlouvy o smlouvě budoucí kupní. Seznamte se s kompletními podmínkami a postupem ve Všeobecných obchodních podmínkách.',
            ],
            [
                'pro_koho' => 'Pro realitní makléře',
                'otazka' => 'Koho považujete za realitního makléře?',
                'odpoved' => 'Na našem portálu odlišujeme osoby, které zastupují a vykonávají vůli vlastníka projektu, od osob, které vykonávají vázanou živnost realitní zprostředkování dle zákona č. 39/2020 Sb. Zákon o realitním zprostředkování a o změně souvisejících zákonů, které považujeme definičně za realitní makléře. Realitní makléř je pro nás každý, kdy má s vlastníkem uzavřenou smlouvu o zprostředkování (resp. smlouvu o realitním zprostředkování).',
            ],
            [
                'pro_koho' => 'Pro realitní makléře',
                'otazka' => 'Jakým způsobem navazujeme spolupráci s realitními makléři?',
                'odpoved' => 'S realitními makléři podepisujeme mandátní smlouvu, ve které si definujeme vzájemné vztahy včetně provize z úspěšného prodeje projektu skrze náš portál.',
            ],
        ]);
    }
}
