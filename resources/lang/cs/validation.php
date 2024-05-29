<?php

return [
    'accepted'             => 'Pole :attribute musí být akceptováno.',
    'active_url'           => 'Pole :attribute není platná URL adresa.',
    'after'                => 'Pole :attribute musí být datum po :date.',
    'after_or_equal'       => 'Pole :attribute musí být datum po nebo rovno :date.',
    'alpha'                => 'Pole :attribute může obsahovat pouze písmena.',
    'alpha_dash'           => 'Pole :attribute může obsahovat pouze písmena, číslice, pomlčky a podtržítka.',
    'alpha_num'            => 'Pole :attribute může obsahovat pouze písmena a číslice.',
    'array'                => 'Pole :attribute musí být pole.',
    'before'               => 'Pole :attribute musí být datum před :date.',
    'before_or_equal'      => 'Pole :attribute musí být datum před nebo rovno :date.',
    'between'              => [
        'numeric' => 'Pole :attribute musí být mezi :min a :max.',
        'file'    => 'Pole :attribute musí mít mezi :min a :max kilobajty.',
        'string'  => 'Pole :attribute musí mít mezi :min a :max znaky.',
        'array'   => 'Pole :attribute musí mít mezi :min a :max položky.',
    ],
    'boolean'              => 'Pole :attribute musí být pravda nebo nepravda.',
    'confirmed'            => 'Pole :attribute potvrzení se neshoduje.',
    'date'                 => 'Pole :attribute není platné datum.',
    'date_equals'          => 'Pole :attribute musí být datum rovno :date.',
    'date_format'          => 'Pole :attribute neodpovídá formátu :format.',
    'different'            => 'Pole :attribute a :other musí být odlišné.',
    'digits'               => 'Pole :attribute musí mít :digits číslic.',
    'digits_between'       => 'Pole :attribute musí mít mezi :min a :max číslicemi.',
    'dimensions'           => 'Pole :attribute má neplatné rozměry obrázku.',
    'distinct'             => 'Pole :attribute má duplicitní hodnotu.',
    'email'                => 'Pole :attribute musí být platná emailová adresa.',
    'ends_with'            => 'Pole :attribute musí končit jedním z následujících: :values.',
    'exists'               => 'Vybrané pole :attribute je neplatné.',
    'file'                 => 'Pole :attribute musí být soubor.',
    'filled'               => 'Pole :attribute musí být vyplněno.',
    'gt'                   => [
        'numeric' => 'Pole :attribute musí být větší než :value.',
        'file'    => 'Pole :attribute musí mít více než :value kilobajtů.',
        'string'  => 'Pole :attribute musí mít více než :value znaků.',
        'array'   => 'Pole :attribute musí mít více než :value položek.',
    ],
    'gte'                  => [
        'numeric' => 'Pole :attribute musí být větší nebo rovno :value.',
        'file'    => 'Pole :attribute musí mít :value kilobajtů nebo více.',
        'string'  => 'Pole :attribute musí mít :value znaků nebo více.',
        'array'   => 'Pole :attribute musí mít :value položek nebo více.',
    ],
    'image'                => 'Pole :attribute musí být obrázek.',
    'in'                   => 'Vybrané pole :attribute je neplatné.',
    'in_array'             => 'Pole :attribute neexistuje v :other.',
    'integer'              => 'Pole :attribute musí být celé číslo.',
    'ip'                   => 'Pole :attribute musí být platná IP adresa.',
    'ipv4'                 => 'Pole :attribute musí být platná IPv4 adresa.',
    'ipv6'                 => 'Pole :attribute musí být platná IPv6 adresa.',
    'json'                 => 'Pole :attribute musí být platný JSON řetězec.',
    'lt'                   => [
        'numeric' => 'Pole :attribute musí být menší než :value.',
        'file'    => 'Pole :attribute musí mít méně než :value kilobajtů.',
        'string'  => 'Pole :attribute musí mít méně než :value znaků.',
        'array'   => 'Pole :attribute musí mít méně než :value položek.',
    ],
    'lte'                  => [
        'numeric' => 'Pole :attribute musí být menší nebo rovno :value.',
        'file'    => 'Pole :attribute nesmí být větší než :value kilobajtů.',
        'string'  => 'Pole :attribute nesmí být delší než :value znaků.',
        'array'   => 'Pole :attribute nesmí mít více než :value položek.',
    ],
    'max'                  => [
        'numeric' => 'Pole :attribute nesmí být větší než :max.',
        'file'    => 'Pole :attribute nesmí být větší než :max kilobajtů.',
        'string'  => 'Pole :attribute nesmí být delší než :max znaků.',
        'array'   => 'Pole :attribute nesmí mít více než :max položek.',
    ],
    'mimes'                => 'Pole :attribute musí být soubor typu: :values.',
    'mimetypes'            => 'Pole :attribute musí být soubor typu: :values.',
    'min'                  => [
        'numeric' => 'Pole :attribute musí být alespoň :min.',
        'file'    => 'Pole :attribute musí mít alespoň :min kilobajtů.',
        'string'  => 'Pole :attribute musí mít alespoň :min znaků.',
        'array'   => 'Pole :attribute musí mít alespoň :min položek.',
    ],
    'not_in'               => 'Vybrané pole :attribute je neplatné.',
    'not_regex'            => 'Formát pole :attribute je neplatný.',
    'numeric'              => 'Pole :attribute musí být číslo.',
    'password'             => 'Heslo je nesprávné.',
    'present'              => 'Pole :attribute musí být přítomno.',
    'regex'                => 'Formát pole :attribute je neplatný.',
    'required'             => 'Pole :attribute je povinné.',
    'required_if'          => 'Pole :attribute je povinné, když :other je :value.',
    'required_unless'      => 'Pole :attribute je povinné, pokud :other není v :values.',
    'required_with'        => 'Pole :attribute je povinné, když :values je přítomno.',
    'required_with_all'    => 'Pole :attribute je povinné, když :values jsou přítomny.',
    'required_without'     => 'Pole :attribute je povinné, když :values není přítomno.',
    'required_without_all' => 'Pole :attribute je povinné, když žádné z :values nejsou přítomny.',
    'same'                 => 'Pole :attribute a :other se musí shodovat.',
    'size'                 => [
        'numeric' => 'Pole :attribute musí být :size.',
        'file'    => 'Pole :attribute musí mít :size kilobajtů.',
        'string'  => 'Pole :attribute musí mít :size znaků.',
        'array'   => 'Pole :attribute musí obsahovat :size položek.',
    ],
    'starts_with'          => 'Pole :attribute musí začínat jedním z následujících: :values.',
    'string'               => 'Pole :attribute musí být řetězec.',
    'timezone'             => 'Pole :attribute musí být platná zóna.',
    'unique'               => 'Pole :attribute je již použito.',
    'uploaded'             => 'Pole :attribute se nepodařilo nahrát.',
    'url'                  => 'Formát pole :attribute je neplatný.',
    'uuid'                 => 'Pole :attribute musí být platný UUID.',

    /*
    |--------------------------------------------------------------------------
    | Vlastní validační zprávy
    |--------------------------------------------------------------------------
    |
    | Zde můžete specifikovat vlastní validační zprávy pro atributy s použitím
    | konvence "attribute.rule" pro pojmenování řádků. To dává možnost rychle
    | specifikovat konkrétní vlastní zprávy pro dané atributy.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'vlastní-zpráva',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Vlastní názvy atributů
    |--------------------------------------------------------------------------
    |
    | Následující řádky se používají k výměně názvů atributů s čitelnějším
    | textem, jako například "E-Mail Address" namísto "email". To nám pomáhá
    | zpříjemnit zprávy.
    |
    */

    'attributes' => [],

];

