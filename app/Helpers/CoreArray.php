<?php

if (!function_exists("month")) {
    function month($input = null)
    {
        $output = [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getTableColumn")) {
    function getTableColumn($input = null)
    {
        $output = [
            TABLE_COLUMN_PRODUCT => 'Product',
            TABLE_COLUMN_PLAN => 'Plan',
            TABLE_COLUMN_PLAN_CODE => 'Plan Code',
            TABLE_COLUMN_PRICE => 'Price',
            TABLE_SETUP_FEE => 'SetUp Fee',
            TABLE_COLUMN_QUANTITY => 'Quantity',
            TABLE_COLUMN_TOTAL => 'Total',
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}


function country($input = null)
{
    $output = [
        'af' => 'Afghanistan',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'ds' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua and Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia',
        'ba' => 'Bosnia and Herzegovina',
        'bw' => 'Botswana',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina ',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'hr' => 'Croatia',
        'cu' => 'Cuba',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'cg' => 'Congo',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'tp' => 'East Timor',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands',
        'fo' => 'Faroe ',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'pf' => 'French Polynesia',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gk' => 'Guernsey',
        'gn' => 'Guinea',
        'gw' => 'Guinea-',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'im' => 'Isle of ',
        'il' => 'Israel',
        'it' => 'Italy',
        'ci' => 'Ivory ',
        'jm' => 'Jamaica',
        'jp' => 'Japan',
        'je' => 'Jersey',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kp' => 'North Korea',
        'kr' => 'South Korea',
        'xk' => 'Kosovo',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Laos',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macau',
        'mk' => 'Macedonia',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'ty' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia',
        'md' => 'Moldova, Republic of',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'me' => 'Montenegro',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'an' => 'Netherlands Antilles',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'ps' => 'Palestine',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'qa' => 'Qatar',
        're' => 'Reunion',
        'ro' => 'Romania',
        'ru' => 'Russian',
        'rw' => 'Rwanda',
        'kn' => 'Saint Kitts and Nevis',
        'lc' => 'Saint Lucia',
        'vc' => 'Saint Vincent and the Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome and ',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'rs' => 'Serbia',
        'sc' => 'Seychelles',
        'sl' => 'Sierra ',
        'sg' => 'Singapore',
        'sk' => 'Slovakia',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard and Jan Mayen ',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syria',
        'tw' => 'Taiwan',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania',
        'th' => 'Thailand',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad and Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks and Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United ',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        'va' => 'Vatican City State',
        've' => 'Venezuela',
        'vn' => 'Vietnam',
        'vi' => 'Virgin Islands (U.S.)',
        'wf' => 'Wallis and Futuna Islands',
        'eh' => 'Western ',
        'ye' => 'Yemen',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe'
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function languageIsoCode($input = null)
{
    $output = [
        "af" => "Afrikaans",
        "sq" => "shqip",
        "am" => "አማርኛ",
        "ar" => "العربية",
        "an" => "aragonés",
        "hy" => "հայերեն",
        "ast" => "asturianu",
        "az" => "azərbaycan dili",
        "eu" => "euskara",
        "be" => "беларуская",
        "bn" => "বাংলা",
        "bs" => "bosanski",
        "br" => "brezhoneg",
        "bg" => "български",
        "ca" => "català",
        "ckb" => "کوردی (دەستنوسی عەرەبی)",
        "zh" => "中文",
        "zh-HK" => "中文（香港）",
        "zh-CN" => "中文（简体）",
        "zh-TW" => "中文（繁體）",
        "co" => "Corsican",
        "hr" => "hrvatski",
        "cs" => "čeština",
        "da" => "dansk",
        "nl" => "Nederlands",
        "en" => "English",
        "en-AU" => "English (Australia)",
        "en-CA" => "English (Canada)",
        "en-IN" => "English (India)",
        "en-NZ" => "English (New Zealand)",
        "en-ZA" => "English (South Africa)",
        "en-GB" => "English (United Kingdom)",
        "en-US" => "English (United States)",
        "eo" => "esperanto",
        "et" => "eesti",
        "fo" => "føroyskt",
        "fil" => "Filipino",
        "fi" => "suomi",
        "fr" => "français",
        "fr-CA" => "français (Canada)",
        "fr-FR" => "français (France)",
        "fr-CH" => "français (Suisse)",
        "gl" => "galego",
        "ka" => "ქართული",
        "de" => "Deutsch",
        "de-AT" => "Deutsch (Österreich)",
        "de-DE" => "Deutsch (Deutschland)",
        "de-LI" => "Deutsch (Liechtenstein)",
        "de-CH" => "Deutsch (Schweiz)",
        "el" => "Ελληνικά",
        "gn" => "Guarani",
        "gu" => "ગુજરાતી",
        "ha" => "Hausa",
        "haw" => "ʻŌlelo Hawaiʻi",
        "he" => "עברית",
        "hi" => "हिन्दी",
        "hu" => "magyar",
        "is" => "íslenska",
        "id" => "Indonesia",
        "ia" => "Interlingua",
        "ga" => "Gaeilge",
        "it" => "italiano",
        "it-IT" => "italiano (Italia)",
        "it-CH" => "italiano (Svizzera)",
        "ja" => "日本語",
        "kn" => "ಕನ್ನಡ",
        "kk" => "қазақ тілі",
        "km" => "ខ្មែរ",
        "ko" => "한국어",
        "ku" => "Kurdî",
        "ky" => "кыргызча",
        "lo" => "ລາວ",
        "la" => "Latin",
        "lv" => "latviešu",
        "ln" => "lingála",
        "lt" => "lietuvių",
        "mk" => "македонски",
        "ms" => "Bahasa Melayu",
        "ml" => "മലയാളം",
        "mt" => "Malti",
        "mr" => "मराठी",
        "mn" => "монгол",
        "ne" => "नेपाली",
        "no" => "norsk",
        "nb" => "norsk bokmål",
        "nn" => "nynorsk",
        "oc" => "Occitan",
        "or" => "ଓଡ଼ିଆ",
        "om" => "Oromoo",
        "ps" => "پښتو",
        "fa" => "فارسی",
        "pl" => "polski",
        "pt" => "português",
        "pt-BR" => "português (Brasil)",
        "pt-PT" => "português (Portugal)",
        "pa" => "ਪੰਜਾਬੀ",
        "qu" => "Quechua",
        "ro" => "română",
        "mo" => "română (Moldova)",
        "rm" => "rumantsch",
        "ru" => "русский",
        "gd" => "Scottish Gaelic",
        "sr" => "српски",
        "sh" => "Croatian",
        "sn" => "chiShona",
        "sd" => "Sindhi",
        "si" => "සිංහල",
        "sk" => "slovenčina",
        "sl" => "slovenščina",
        "so" => "Soomaali",
        "st" => "Southern Sotho",
        "es" => "español",
        "es-AR" => "español (Argentina)",
        "es-419" => "español (Latinoamérica)",
        "es-MX" => "español (México)",
        "es-ES" => "español (España)",
        "es-US" => "español (Estados Unidos)",
        "su" => "Sundanese",
        "sw" => "Kiswahili",
        "sv" => "svenska",
        "tg" => "тоҷикӣ",
        "ta" => "தமிழ்",
        "tt" => "Tatar",
        "te" => "తెలుగు",
        "th" => "ไทย",
        "ti" => "ትግርኛ",
        "to" => "lea fakatonga",
        "tr" => "Türkçe",
        "tk" => "Turkmen",
        "tw" => "Twi",
        "uk" => "українська",
        "ur" => "اردو",
        "ug" => "Uyghur",
        "uz" => "o‘zbek",
        "vi" => "Tiếng Việt",
        "wa" => "wa",
        "cy" => "Cymraeg",
        "fy" => "Western Frisian",
        "xh" => "Xhosa",
        "yi" => "Yiddish",
        "yo" => "Èdè Yorùbá",
        "zu" => "isiZulu"
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

function getCurrency($currency = null, $only_symbol = false)
{
    $currency_list = array(
        "AFA" => array("name" => "Afghan Afghani", "symbol" => "؋"),
        "ALL" => array("name" => "Albanian Lek", "symbol" => "Lek"),
        "DZD" => array("name" => "Algerian Dinar", "symbol" => "دج"),
        "AOA" => array("name" => "Angolan Kwanza", "symbol" => "Kz"),
        "ARS" => array("name" => "Argentine Peso", "symbol" => "$"),
        "AMD" => array("name" => "Armenian Dram", "symbol" => "֏"),
        "AWG" => array("name" => "Aruban Florin", "symbol" => "ƒ"),
        "AUD" => array("name" => "Australian Dollar", "symbol" => "$"),
        "AZN" => array("name" => "Azerbaijani Manat", "symbol" => "m"),
        "BSD" => array("name" => "Bahamian Dollar", "symbol" => "B$"),
        "BHD" => array("name" => "Bahraini Dinar", "symbol" => ".د.ب"),
        "BDT" => array("name" => "Bangladeshi Taka", "symbol" => "৳"),
        "BBD" => array("name" => "Barbadian Dollar", "symbol" => "Bds$"),
        "BYR" => array("name" => "Belarusian Ruble", "symbol" => "Br"),
        "BEF" => array("name" => "Belgian Franc", "symbol" => "fr"),
        "BZD" => array("name" => "Belize Dollar", "symbol" => "$"),
        "BMD" => array("name" => "Bermudan Dollar", "symbol" => "$"),
        "BTN" => array("name" => "Bhutanese Ngultrum", "symbol" => "Nu."),
        "BTC" => array("name" => "Bitcoin", "symbol" => "฿"),
        "BOB" => array("name" => "Bolivian Boliviano", "symbol" => "Bs."),
        "BAM" => array("name" => "Bosnia", "symbol" => "KM"),
        "BWP" => array("name" => "Botswanan Pula", "symbol" => "P"),
        "BRL" => array("name" => "Brazilian Real", "symbol" => "R$"),
        "GBP" => array("name" => "British Pound Sterling", "symbol" => "£"),
        "BND" => array("name" => "Brunei Dollar", "symbol" => "B$"),
        "BGN" => array("name" => "Bulgarian Lev", "symbol" => "Лв."),
        "BIF" => array("name" => "Burundian Franc", "symbol" => "FBu"),
        "KHR" => array("name" => "Cambodian Riel", "symbol" => "KHR"),
        "CAD" => array("name" => "Canadian Dollar", "symbol" => "$"),
        "CVE" => array("name" => "Cape Verdean Escudo", "symbol" => "$"),
        "KYD" => array("name" => "Cayman Islands Dollar", "symbol" => "$"),
        "XOF" => array("name" => "CFA Franc BCEAO", "symbol" => "CFA"),
        "XAF" => array("name" => "CFA Franc BEAC", "symbol" => "FCFA"),
        "XPF" => array("name" => "CFP Franc", "symbol" => "₣"),
        "CLP" => array("name" => "Chilean Peso", "symbol" => "$"),
        "CNY" => array("name" => "Chinese Yuan", "symbol" => "¥"),
        "COP" => array("name" => "Colombian Peso", "symbol" => "$"),
        "KMF" => array("name" => "Comorian Franc", "symbol" => "CF"),
        "CDF" => array("name" => "Congolese Franc", "symbol" => "FC"),
        "CRC" => array("name" => "Costa Rican ColÃ³n", "symbol" => "₡"),
        "HRK" => array("name" => "Croatian Kuna", "symbol" => "kn"),
        "CUC" => array("name" => "Cuban Convertible Peso", "symbol" => "$, CUC"),
        "CZK" => array("name" => "Czech Republic Koruna", "symbol" => "Kč"),
        "DKK" => array("name" => "Danish Krone", "symbol" => "Kr."),
        "DJF" => array("name" => "Djiboutian Franc", "symbol" => "Fdj"),
        "DOP" => array("name" => "Dominican Peso", "symbol" => "$"),
        "XCD" => array("name" => "East Caribbean Dollar", "symbol" => "$"),
        "EGP" => array("name" => "Egyptian Pound", "symbol" => "ج.م"),
        "ERN" => array("name" => "Eritrean Nakfa", "symbol" => "Nfk"),
        "EEK" => array("name" => "Estonian Kroon", "symbol" => "kr"),
        "ETB" => array("name" => "Ethiopian Birr", "symbol" => "Nkf"),
        "EUR" => array("name" => "Euro", "symbol" => "€"),
        "FKP" => array("name" => "Falkland Islands Pound", "symbol" => "£"),
        "FJD" => array("name" => "Fijian Dollar", "symbol" => "FJ$"),
        "GMD" => array("name" => "Gambian Dalasi", "symbol" => "D"),
        "GEL" => array("name" => "Georgian Lari", "symbol" => "ლ"),
        "DEM" => array("name" => "German Mark", "symbol" => "DM"),
        "GHS" => array("name" => "Ghanaian Cedi", "symbol" => "GH₵"),
        "GIP" => array("name" => "Gibraltar Pound", "symbol" => "£"),
        "GRD" => array("name" => "Greek Drachma", "symbol" => "₯, Δρχ, Δρ"),
        "GTQ" => array("name" => "Guatemalan Quetzal", "symbol" => "Q"),
        "GNF" => array("name" => "Guinean Franc", "symbol" => "FG"),
        "GYD" => array("name" => "Guyanaese Dollar", "symbol" => "$"),
        "HTG" => array("name" => "Haitian Gourde", "symbol" => "G"),
        "HNL" => array("name" => "Honduran Lempira", "symbol" => "L"),
        "HKD" => array("name" => "Hong Kong Dollar", "symbol" => "$"),
        "HUF" => array("name" => "Hungarian Forint", "symbol" => "Ft"),
        "ISK" => array("name" => "Icelandic KrÃ³na", "symbol" => "kr"),
        "INR" => array("name" => "Indian Rupee", "symbol" => "₹"),
        "IDR" => array("name" => "Indonesian Rupiah", "symbol" => "Rp"),
        "IRR" => array("name" => "Iranian Rial", "symbol" => "﷼"),
        "IQD" => array("name" => "Iraqi Dinar", "symbol" => "د.ع"),
        "ILS" => array("name" => "Israeli New Sheqel", "symbol" => "₪"),
        "ITL" => array("name" => "Italian Lira", "symbol" => "L,£"),
        "JMD" => array("name" => "Jamaican Dollar", "symbol" => "J$"),
        "JPY" => array("name" => "Japanese Yen", "symbol" => "¥"),
        "JOD" => array("name" => "Jordanian Dinar", "symbol" => "ا.د"),
        "KZT" => array("name" => "Kazakhstani Tenge", "symbol" => "лв"),
        "KES" => array("name" => "Kenyan Shilling", "symbol" => "KSh"),
        "KWD" => array("name" => "Kuwaiti Dinar", "symbol" => "ك.د"),
        "KGS" => array("name" => "Kyrgystani Som", "symbol" => "лв"),
        "LAK" => array("name" => "Laotian Kip", "symbol" => "₭"),
        "LVL" => array("name" => "Latvian Lats", "symbol" => "Ls"),
        "LBP" => array("name" => "Lebanese Pound", "symbol" => "£"),
        "LSL" => array("name" => "Lesotho Loti", "symbol" => "L"),
        "LRD" => array("name" => "Liberian Dollar", "symbol" => "$"),
        "LYD" => array("name" => "Libyan Dinar", "symbol" => "د.ل"),
        "LTL" => array("name" => "Lithuanian Litas", "symbol" => "Lt"),
        "MOP" => array("name" => "Macanese Pataca", "symbol" => "$"),
        "MKD" => array("name" => "Macedonian Denar", "symbol" => "ден"),
        "MGA" => array("name" => "Malagasy Ariary", "symbol" => "Ar"),
        "MWK" => array("name" => "Malawian Kwacha", "symbol" => "MK"),
        "MYR" => array("name" => "Malaysian Ringgit", "symbol" => "RM"),
        "MVR" => array("name" => "Maldivian Rufiyaa", "symbol" => "Rf"),
        "MRO" => array("name" => "Mauritanian Ouguiya", "symbol" => "MRU"),
        "MUR" => array("name" => "Mauritian Rupee", "symbol" => "₨"),
        "MXN" => array("name" => "Mexican Peso", "symbol" => "$"),
        "MDL" => array("name" => "Moldovan Leu", "symbol" => "L"),
        "MNT" => array("name" => "Mongolian Tugrik", "symbol" => "₮"),
        "MAD" => array("name" => "Moroccan Dirham", "symbol" => "MAD"),
        "MZM" => array("name" => "Mozambican Metical", "symbol" => "MT"),
        "MMK" => array("name" => "Myanmar Kyat", "symbol" => "K"),
        "NAD" => array("name" => "Namibian Dollar", "symbol" => "$"),
        "NPR" => array("name" => "Nepalese Rupee", "symbol" => "₨"),
        "ANG" => array("name" => "Netherlands Antillean Guilder", "symbol" => "ƒ"),
        "TWD" => array("name" => "New Taiwan Dollar", "symbol" => "$"),
        "NZD" => array("name" => "New Zealand Dollar", "symbol" => "$"),
        "NIO" => array("name" => "Nicaraguan CÃ³rdoba", "symbol" => "C$"),
        "NGN" => array("name" => "Nigerian Naira", "symbol" => "₦"),
        "KPW" => array("name" => "North Korean Won", "symbol" => "₩"),
        "NOK" => array("name" => "Norwegian Krone", "symbol" => "kr"),
        "OMR" => array("name" => "Omani Rial", "symbol" => ".ع.ر"),
        "PKR" => array("name" => "Pakistani Rupee", "symbol" => "₨"),
        "PAB" => array("name" => "Panamanian Balboa", "symbol" => "B/."),
        "PGK" => array("name" => "Papua New Guinean Kina", "symbol" => "K"),
        "PYG" => array("name" => "Paraguayan Guarani", "symbol" => "₲"),
        "PEN" => array("name" => "Peruvian Nuevo Sol", "symbol" => "S/."),
        "PHP" => array("name" => "Philippine Peso", "symbol" => "₱"),
        "PLN" => array("name" => "Polish Zloty", "symbol" => "zł"),
        "QAR" => array("name" => "Qatari Rial", "symbol" => "ق.ر"),
        "RON" => array("name" => "Romanian Leu", "symbol" => "lei"),
        "RUB" => array("name" => "Russian Ruble", "symbol" => "₽"),
        "RWF" => array("name" => "Rwandan Franc", "symbol" => "FRw"),
        "SVC" => array("name" => "Salvadoran ColÃ³n", "symbol" => "₡"),
        "WST" => array("name" => "Samoan Tala", "symbol" => "SAT"),
        "SAR" => array("name" => "Saudi Riyal", "symbol" => "﷼"),
        "RSD" => array("name" => "Serbian Dinar", "symbol" => "din"),
        "SCR" => array("name" => "Seychellois Rupee", "symbol" => "SRe"),
        "SLL" => array("name" => "Sierra Leonean Leone", "symbol" => "Le"),
        "SGD" => array("name" => "Singapore Dollar", "symbol" => "$"),
        "SKK" => array("name" => "Slovak Koruna", "symbol" => "Sk"),
        "SBD" => array("name" => "Solomon Islands Dollar", "symbol" => "Si$"),
        "SOS" => array("name" => "Somali Shilling", "symbol" => "Sh.so."),
        "ZAR" => array("name" => "South African Rand", "symbol" => "R"),
        "KRW" => array("name" => "South Korean Won", "symbol" => "₩"),
        "XDR" => array("name" => "Special Drawing Rights", "symbol" => "SDR"),
        "LKR" => array("name" => "Sri Lankan Rupee", "symbol" => "Rs"),
        "SHP" => array("name" => "St. Helena Pound", "symbol" => "£"),
        "SDG" => array("name" => "Sudanese Pound", "symbol" => ".س.ج"),
        "SRD" => array("name" => "Surinamese Dollar", "symbol" => "$"),
        "SZL" => array("name" => "Swazi Lilangeni", "symbol" => "E"),
        "SEK" => array("name" => "Swedish Krona", "symbol" => "kr"),
        "CHF" => array("name" => "Swiss Franc", "symbol" => "CHf"),
        "SYP" => array("name" => "Syrian Pound", "symbol" => "LS"),
        "STD" => array("name" => "São Tomé and Príncipe Dobra", "symbol" => "Db"),
        "TJS" => array("name" => "Tajikistani Somoni", "symbol" => "SM"),
        "TZS" => array("name" => "Tanzanian Shilling", "symbol" => "TSh"),
        "THB" => array("name" => "Thai Baht", "symbol" => "฿"),
        "TOP" => array("name" => "Tongan pa'anga", "symbol" => "$"),
        "TTD" => array("name" => "Trinidad & Tobago Dollar", "symbol" => "$"),
        "TND" => array("name" => "Tunisian Dinar", "symbol" => "ت.د"),
        "TRY" => array("name" => "Turkish Lira", "symbol" => "₺"),
        "TMT" => array("name" => "Turkmenistani Manat", "symbol" => "T"),
        "UGX" => array("name" => "Ugandan Shilling", "symbol" => "USh"),
        "UAH" => array("name" => "Ukrainian Hryvnia", "symbol" => "₴"),
        "AED" => array("name" => "United Arab Emirates Dirham", "symbol" => "إ.د"),
        "UYU" => array("name" => "Uruguayan Peso", "symbol" => "$"),
        "USD" => array("name" => "US Dollar", "symbol" => "$"),
        "UZS" => array("name" => "Uzbekistan Som", "symbol" => "лв"),
        "VUV" => array("name" => "Vanuatu Vatu", "symbol" => "VT"),
        "VEF" => array("name" => "Venezuelan BolÃvar", "symbol" => "Bs"),
        "VND" => array("name" => "Vietnamese Dong", "symbol" => "₫"),
        "YER" => array("name" => "Yemeni Rial", "symbol" => "﷼"),
        "ZMK" => array("name" => "Zambian Kwacha", "symbol" => "ZK")
    );
    if (is_null($currency)) {
        $all_currency = [];
        foreach ($currency_list as $key => $item) {
            if ($only_symbol) {
                $all_currency[$key] = $item['symbol'];
            } else {
                $all_currency[$key] = $item['name'] . '(' . $item['symbol'] . ')';
            }
        }
        return $all_currency;
    } else {
        if ($only_symbol) {
            return $currency_list[$currency]['symbol'];
        } else {
            return $currency_list[$currency]['name'] . '(' . $currency_list[$currency]['symbol'] . ')';
        }
    }
}

if (!function_exists("getCountry")) {
    function getCountry($input = null)
    {
        $countries = [
            'AF' => 'Afghanistan',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua and Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia, Plurinational State of',
            'BQ' => 'Bonaire, Sint Eustatius and Saba',
            'BA' => 'Bosnia and Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'CV' => 'Cabo Verde',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic of the',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CW' => 'Curaçao',
            'CY' => 'Cyprus',
            'CZ' => 'Czechia',
            'CI' => 'Côte d\'Ivoire',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'SZ' => 'Eswatini',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island and McDonald Islands',
            'VA' => 'Holy See',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KP' => 'Korea, Democratic People\'s Republic of',
            'KR' => 'Korea, Republic of',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States of',
            'MD' => 'Moldova, Republic of',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands, Kingdom of the',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MK' => 'North Macedonia',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestine, State of',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'RE' => 'Réunion',
            'BL' => 'Saint Barthélemy',
            'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
            'KN' => 'Saint Kitts and Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin (French part)',
            'PM' => 'Saint Pierre and Miquelon',
            'VC' => 'Saint Vincent and the Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome and Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SX' => 'Sint Maarten (Dutch part)',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia and the South Sandwich Islands',
            'SS' => 'South Sudan',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard and Jan Mayen',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan, Province of China',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania, United Republic of',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad and Tobago',
            'TN' => 'Tunisia',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks and Caicos Islands',
            'TV' => 'Tuvalu',
            'TR' => 'Türkiye',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom of Great Britain and Northern Ireland',
            'UM' => 'United States Minor Outlying Islands',
            'US' => 'United States of America',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela, Bolivarian Republic of',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands (British)',
            'VI' => 'Virgin Islands (U.S.)',
            'WF' => 'Wallis and Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
            'AX' => 'Åland Islands',
        ];

        if (is_null($input)) {
            return $countries;
        }

        return $countries[$input] ?? $input;
    }
}



if (!function_exists("getRoleName")) {
    function getRoleName($input = null)
    {
        $output = [
            USER_ROLE_ADMIN => __('Admin'),
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getMessage")) {
    function getMessage($input = null)
    {
        $output = [
            CREATED_SUCCESSFULLY => __("Created Successfully"),
            UPDATED_SUCCESSFULLY => __("Updated Successfully"),
            DELETED_SUCCESSFULLY => __("Deleted Successfully"),
            UPLOADED_SUCCESSFULLY => __("Uploaded Successfully"),
            DATA_FETCH_SUCCESSFULLY => __("Data Fetch Successfully"),
            SENT_SUCCESSFULLY => __("Sent Successfully"),
            SOMETHING_WENT_WRONG => __("Something went wrong! Please try again"),
            DO_NOT_HAVE_PERMISSION => __("You don\'t have the permission"),
            STATUS_UPDATED_SUCCESSFULLY => __("Status Updated Successfully"),
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getStatus")) {
    function getStatus($input = null)
    {
        $output = [
            STATUS_PENDING => __("Pending"),
            STATUS_SUCCESS => __("Success"),
            STATUS_ACTIVE => __("Active"),
            STATUS_DISABLE => __("Disabled"),
            STATUS_DEACTIVATE => __("Deactivate"),
            STATUS_SUSPENDED => __("Suspended"),
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getDurationType")) {
    function getDurationType($input = null)
    {
        $output = [
            DURATION_TYPE_DAY => __("Day"),
            DURATION_TYPE_MONTH => __("Month"),
            DURATION_TYPE_YEAR => __("Year"),
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getReturnType")) {
    function getReturnType($input = null)
    {
        $output = [
            RETURN_TYPE_FIXED => __("Fixed"),
            RETURN_TYPE_RANDOM => __("Random"),
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getPageType")) {
    function getPageType($input = null)
    {
        $output = [
            PAGE_ABOUT_US => 'About Us',
            PAGE_PRIVACY_POLICY => 'Privacy Policy',
            PAGE_TERMS_OF_SERVICE => 'Terms Of Service',
            PAGE_COOKIE_POLICY => 'Cookie Policy',
            PAGE_REFUND_POLICY => 'Refund Policy',
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("walletDepositType")) {
    function walletDepositType($input = null)
    {
        $output = [
            DEPOSIT_TYPE_BUY => 'Buy',
            DEPOSIT_TYPE_DEPOSIT => 'Deposit'
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}
if (!function_exists("orderHistoryType")) {
    function orderHistoryType($input = null)
    {
        $output = [
            ORDER_TYPE_DEPOSIT => 'Deposit',
            ORDER_TYPE_HARDWARE => 'Hardware',
            ORDER_TYPE_PLAN => 'Plan'
        ];


        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getDateFormatList")) {
    function getDateFormatList($input = null)
    {
        $output = [
            'd-m-Y' => 'd-m-Y'
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getTimeList")) {
    function getTimeList($input = null)
    {
        $output = [
            'H:i:s' => 'H:i:s'
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

function getPaymentServiceClass($input = null)
{
    $output = array(
        PAYPAL => 'App\Http\Services\Payment\PaypalService',
        STRIPE => 'App\Http\Services\Payment\StripeService',
        RAZORPAY => 'App\Http\Services\Payment\RazorpayService',
        INSTAMOJO => 'App\Http\Services\Payment\InstamojoService',
        MOLLIE => 'App\Http\Services\Payment\MollieService',
        COINBASE => 'App\Http\Services\Payment\CoinbaseService',
        PAYSTACK => 'App\Http\Services\Payment\PaystackService',
        SSLCOMMERZ => 'App\Http\Services\Payment\SslCommerzService',
        MERCADOPAGO => 'App\Http\Services\Payment\MercadoPagoService',
        FLUTTERWAVE => 'App\Http\Services\Payment\FlutterwaveService',
        IYZICO => 'App\Http\Services\Payment\IyzipayService',
        BITPAY => 'App\Http\Services\Payment\BitPayService',
        ZITOPAY => 'App\Http\Services\Payment\ZitoPayService',
        BINANCE => 'App\Http\Services\Payment\BinancePaymentService',
        PAYTM => 'App\Http\Services\Payment\PaytmService',
        PAYHERE => 'App\Http\Services\Payment\PayHerePaymentService',
        MAXICASH => 'App\Http\Services\Payment\MaxiCashService',
        CINETPAY => 'App\Http\Services\Payment\CinetPayService',
        VOGUEPAY => 'App\Http\Services\Payment\VoguePayService',
        TOYYIBPAY => 'App\Http\Services\Payment\ToyyibPayService',
        PAYMOB => 'App\Http\Services\Payment\PaymobService',
        AUTHORIZE  => 'App\Http\Services\Payment\AuthorizeNetService',
        ALIPAY => 'App\Http\Services\Payment\AlipayService',
        PADDLE => 'App\Http\Services\Payment\PaddleService',
        XENDIT => 'App\Http\Services\Payment\XenditService',
        BANK => 'App\Http\Services\Payment\BankService',
    );
    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input] ?? '';
    }
}


if (!function_exists("eventType")) {
    function eventType($input = null)
    {
        $output = [
            EVENT_TYPE_FREE => __('Free'),
            EVENT_TYPE_PAID => __('Paid')
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getEmployeeStatus")) {
    function getEmployeeStatus($input = null)
    {
        $output = [
            FULL_TIME => __("Full Time"),
            PART_TIME => __("Part Time"),
            CONTRACTUAL => __("Contractual"),
            REMOTE_WORKER => __("Remote Worker"),
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists("getJobStatus")) {
    function getJobStatus($input = null)
    {
        $output = [
            JOB_STATUS_PENDING => __("Pending"),
            JOB_STATUS_APPROVED => __("Approved"),
            JOB_STATUS_CANCELED => __("Canceled"),
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}




if (!function_exists("getPaymentStatus")) {
    function getPaymentStatus($input = null)
    {
        $output = [
            STATUS_PENDING => __("Pending"),
            STATUS_ACTIVE => __("Approved"),
            PAYMENT_STATUS_CANCELLED => __("Reject"),
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}




if (!function_exists("getDurationName")) {
    function getDurationName($input = null)
    {
        $output = [
            DURATION_MONTH => __("Month"),
            DURATION_YEAR => __("Year"),
        ];

        if (is_null($input)) {
            return $output;
        } else {
            return $output[$input];
        }
    }
}

if (!function_exists('customNotifyTempFields')) {
    function customNotifyTempFields($type = null)
    {
        $data = [];
    if ($type == 'password-reset') {
        $data = [
            '{{username}}' => '',
            '{{reset_password_url}}' => '',
        ];
    } else if ($type == 'email-verify') {
        $data = [
            '{{username}}' => '',
            '{{otp}}' => '',
        ];
    } else if ($type == 'employee-create-notify') {
        $data = [
            '{{username}}' => '',
            '{{email}}' => '',
            '{{password}}' => ''
        ];
    } else if ($type == 'department-head-assign-notify') {
        $data = [
            '{{username}}' => ''
        ];
    } else if ($type == 'employee-session-assign-notify') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{goal_setup_start_date}}' => '',
            '{{goal_setup_end_date}}' => '',
            '{{session_period_start_date}}' => '',
            '{{session_period_end_date}}' => '',
            '{{session_approval_process_start_date}}' => '',
            '{{session_approval_process_end_date}}' => '',
        ];
    } else if ($type == 'employee-goal-submit-notify') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{goal_setup_start_date}}' => '',
            '{{goal_setup_end_date}}' => '',
            '{{goal_creator_name}}' => '',
        ];
    } else if ($type == 'goal-approved-notify-for-goal-creator') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{feedback}}' => '',
            '{{approval_name}}' => '',
            '{{rating}}' => '',
        ];
    } else if ($type == 'goal-approved-notify-for-next-approval') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{goal_creator_name}}' => '',
            '{{sender_name}}' => '',
            '{{feedback}}' => '',
        ];
    } else if ($type == 'goal-back-notify') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{sender_name}}' => '',
            '{{feedback}}' => '',
        ];
    } else if ($type == 'goal-resubmit-notify') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{goal_creator_name}}' => '',
        ];
    } else if ($type == 'goal-final-approved') {
        $data = [
            '{{username}}' => '',
            '{{session_name}}' => '',
            '{{goal_creator_name}}' => '',
            '{{final_approval_name}}' => '',
            '{{feedback}}' => '',
            '{{rating}}' => '',
        ];
    } else if ($type == 'subscription-paid-notify-for-super-admin') {
        $data = [
            '{{username}}' => '',
            '{{package}}' => '',
            '{{gateway}}' => '',
        ];
    } else if ($type == 'subscription-cancel-notify-for-super-admin') {
        $data = [
            '{{username}}' => '',
            '{{package}}' => '',
        ];
    }else if ($type == 'saas-subscription-notify') {
        $data = [
            '{{username}}' => '',
        ];
    }

    return $data;
    }
}

if (!function_exists('emailTempFields')) {
    function emailTempFields()
    {
        return customNotifyTempFields();
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// SocialAgent Helper Functions
// ═══════════════════════════════════════════════════════════════════════════════

if (!function_exists('platformTypes')) {
    /**
     * Return an array of platform type integers → human-readable labels.
     * Pass an integer to get a single label.
     */
    function platformTypes($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => __('Facebook Page'),
            PLATFORM_MESSENGER     => __('Messenger'),
            PLATFORM_WHATSAPP      => __('WhatsApp Business'),
            PLATFORM_INSTAGRAM     => __('Instagram'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('platformIcons')) {
    /**
     * Return Font Awesome icon class for a platform type.
     */
    function platformIcons($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => 'fa-brands fa-facebook',
            PLATFORM_MESSENGER     => 'fa-brands fa-facebook-messenger',
            PLATFORM_WHATSAPP      => 'fa-brands fa-whatsapp',
            PLATFORM_INSTAGRAM     => 'fa-brands fa-instagram',
        ];
        return is_null($input) ? $output : ($output[$input] ?? 'fa-solid fa-link');
    }
}

if (!function_exists('platformColors')) {
    /**
     * Return a CSS hex colour for each platform.
     */
    function platformColors($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => '#1877F2',
            PLATFORM_MESSENGER     => '#0084FF',
            PLATFORM_WHATSAPP      => '#25D366',
            PLATFORM_INSTAGRAM     => '#E1306C',
        ];
        return is_null($input) ? $output : ($output[$input] ?? '#6B7280');
    }
}

if (!function_exists('conversationStatuses')) {
    /**
     * Return conversation status labels. Pass integer to get single label.
     */
    function conversationStatuses($input = null)
    {
        $output = [
            CONVERSATION_STATUS_OPEN      => __('Open'),
            CONVERSATION_STATUS_RESOLVED  => __('Resolved'),
            CONVERSATION_STATUS_PENDING   => __('Pending'),
            CONVERSATION_STATUS_ESCALATED => __('Escalated'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('conversationStatusBadge')) {
    /**
     * Return a CSS badge class for a conversation status.
     */
    function conversationStatusBadge($input)
    {
        $map = [
            CONVERSATION_STATUS_OPEN      => 'zBadge-active',
            CONVERSATION_STATUS_RESOLVED  => 'zBadge-inactive',
            CONVERSATION_STATUS_PENDING   => 'zBadge-warning',
            CONVERSATION_STATUS_ESCALATED => 'zBadge-danger',
        ];
        return $map[$input] ?? '';
    }
}

if (!function_exists('messageSenderTypes')) {
    /**
     * Return human-readable sender type labels.
     */
    function messageSenderTypes($input = null)
    {
        $output = [
            MESSAGE_SENDER_CUSTOMER    => __('Customer'),
            MESSAGE_SENDER_AI          => __('AI Agent'),
            MESSAGE_SENDER_HUMAN_ADMIN => __('Human Admin'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('keywordMatchTypes')) {
    /**
     * Return keyword rule match type labels.
     */
    function keywordMatchTypes($input = null)
    {
        $output = [
            KEYWORD_MATCH_CONTAINS    => __('Contains'),
            KEYWORD_MATCH_EXACT       => __('Exact Match'),
            KEYWORD_MATCH_STARTS_WITH => __('Starts With'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('keywordActionLabel')) {
    /**
     * Return a human-readable label for a keyword rule action.
     */
    function keywordActionLabel(string $action): string
    {
        return match ($action) {
            KEYWORD_ACTION_REPLY    => __('Send Reply'),
            KEYWORD_ACTION_ESCALATE => __('Escalate to Human'),
            KEYWORD_ACTION_IGNORE   => __('Ignore Message'),
            default                 => __('Send Reply'),
        };
    }
}

if (!function_exists('keywordActionLabels')) {
    /**
     * Return all keyword rule action labels (for dropdowns).
     */
    function keywordActionLabels($input = null)
    {
        $output = [
            KEYWORD_ACTION_REPLY    => __('Send Reply'),
            KEYWORD_ACTION_ESCALATE => __('Escalate to Human'),
            KEYWORD_ACTION_IGNORE   => __('Ignore Message'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('keywordActionColor')) {
    /**
     * Return a CSS hex color for a keyword rule action badge.
     */
    function keywordActionColor(string $action): string
    {
        return match ($action) {
            KEYWORD_ACTION_REPLY    => '#22c55e',
            KEYWORD_ACTION_ESCALATE => '#f59e0b',
            KEYWORD_ACTION_IGNORE   => '#ef4444',
            default                 => '#22c55e',
        };
    }
}

if (!function_exists('replyTemplatePlatforms')) {
    /**
     * Return reply template platform options (for dropdowns).
     */
    function replyTemplatePlatforms($input = null)
    {
        $output = [
            TEMPLATE_PLATFORM_ALL       => __('All Platforms'),
            TEMPLATE_PLATFORM_FACEBOOK  => __('Facebook'),
            TEMPLATE_PLATFORM_WHATSAPP  => __('WhatsApp'),
            TEMPLATE_PLATFORM_INSTAGRAM => __('Instagram'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('All Platforms'));
    }
}

if (!function_exists('aiProviders')) {
    /**
     * Return all supported AI provider labels.
     */
    function aiProviders($input = null)
    {
        $output = [
            AI_PROVIDER_CLAUDE   => 'Claude (Anthropic)',
            AI_PROVIDER_OPENAI   => 'ChatGPT (OpenAI)',
            AI_PROVIDER_GEMINI   => 'Gemini (Google)',
            AI_PROVIDER_GROK     => 'Grok (xAI)',
            AI_PROVIDER_DEEPSEEK => 'DeepSeek',
        ];
        return is_null($input) ? $output : ($output[$input] ?? $input);
    }
}

if (!function_exists('aiProviderColors')) {
    function aiProviderColors($input = null)
    {
        $output = [
            AI_PROVIDER_CLAUDE   => '#D97706',  // amber
            AI_PROVIDER_OPENAI   => '#10b981',  // green
            AI_PROVIDER_GEMINI   => '#3b82f6',  // blue
            AI_PROVIDER_GROK     => '#1a1a1a',  // black
            AI_PROVIDER_DEEPSEEK => '#6366f1',  // indigo
        ];
        return is_null($input) ? $output : ($output[$input] ?? '#6B7280');
    }
}

if (!function_exists('aiProviderIcons')) {
    function aiProviderIcons($input = null)
    {
        $output = [
            AI_PROVIDER_CLAUDE   => 'fa-solid fa-a',
            AI_PROVIDER_OPENAI   => 'fa-solid fa-robot',
            AI_PROVIDER_GEMINI   => 'fa-brands fa-google',
            AI_PROVIDER_GROK     => 'fa-brands fa-x-twitter',
            AI_PROVIDER_DEEPSEEK => 'fa-solid fa-microchip',
        ];
        return is_null($input) ? $output : ($output[$input] ?? 'fa-solid fa-brain');
    }
}

if (!function_exists('aiProviderApiDocs')) {
    /**
     * Return the API key docs URL for each provider.
     */
    function aiProviderApiDocs($input = null)
    {
        $output = [
            AI_PROVIDER_CLAUDE   => 'https://console.anthropic.com/settings/keys',
            AI_PROVIDER_OPENAI   => 'https://platform.openai.com/api-keys',
            AI_PROVIDER_GEMINI   => 'https://aistudio.google.com/apikey',
            AI_PROVIDER_GROK     => 'https://console.x.ai',
            AI_PROVIDER_DEEPSEEK => 'https://platform.deepseek.com/api_keys',
        ];
        return is_null($input) ? $output : ($output[$input] ?? '#');
    }
}

if (!function_exists('aiModelsForProvider')) {
    /**
     * Return available model slugs for a given provider.
     * Pass provider constant to get its models array.
     */
    function aiModelsForProvider(string $provider): array
    {
        return match ($provider) {
            AI_PROVIDER_CLAUDE => [
                AI_MODEL_CLAUDE_SONNET => 'Claude Sonnet 4.5 ✦ Recommended',
                AI_MODEL_CLAUDE_OPUS   => 'Claude Opus 4.5 ✦ Most Powerful',
                AI_MODEL_CLAUDE_HAIKU  => 'Claude Haiku 4.5 ✦ Fastest',
            ],
            AI_PROVIDER_OPENAI => [
                AI_MODEL_GPT4O      => 'GPT-4o ✦ Recommended',
                AI_MODEL_GPT4O_MINI => 'GPT-4o mini ✦ Cheap & Fast',
                AI_MODEL_GPT41      => 'GPT-4.1',
                AI_MODEL_O3_MINI    => 'o3-mini ✦ Reasoning',
            ],
            AI_PROVIDER_GEMINI => [
                AI_MODEL_GEMINI_25_FLASH => 'Gemini 2.5 Flash ✦ Recommended',
                AI_MODEL_GEMINI_25_PRO   => 'Gemini 2.5 Pro ✦ Most Powerful',
                AI_MODEL_GEMINI_20_FLASH => 'Gemini 2.0 Flash',
            ],
            AI_PROVIDER_GROK => [
                AI_MODEL_GROK3      => 'Grok 3 ✦ Recommended',
                AI_MODEL_GROK3_MINI => 'Grok 3 Mini ✦ Faster',
            ],
            AI_PROVIDER_DEEPSEEK => [
                AI_MODEL_DEEPSEEK_CHAT     => 'DeepSeek Chat ✦ Recommended',
                AI_MODEL_DEEPSEEK_REASONER => 'DeepSeek Reasoner ✦ Deep Thinking',
            ],
            default => [],
        };
    }
}

if (!function_exists('aiModelOptions')) {
    /**
     * Flat list of all models across all providers (for simple selects).
     */
    function aiModelOptions($input = null)
    {
        $output = [];
        foreach ([AI_PROVIDER_CLAUDE, AI_PROVIDER_OPENAI, AI_PROVIDER_GEMINI, AI_PROVIDER_GROK, AI_PROVIDER_DEEPSEEK] as $p) {
            foreach (aiModelsForProvider($p) as $slug => $label) {
                $output[$slug] = aiProviders($p) . ' — ' . $label;
            }
        }
        return is_null($input) ? $output : ($output[$input] ?? $input);
    }
}

if (!function_exists('aiLanguageModes')) {
    /**
     * Return available language mode options for AI agent.
     */
    function aiLanguageModes($input = null)
    {
        $output = [
            'auto' => __('Auto Detect'),
            'en'   => __('English'),
            'bn'   => __('Bengali'),
            'ar'   => __('Arabic'),
            'es'   => __('Spanish'),
            'fr'   => __('French'),
            'de'   => __('German'),
            'hi'   => __('Hindi'),
            'zh'   => __('Chinese'),
            'pt'   => __('Portuguese'),
            'ru'   => __('Russian'),
            'ja'   => __('Japanese'),
            'ko'   => __('Korean'),
            'tr'   => __('Turkish'),
            'id'   => __('Indonesian'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? $input);
    }
}


// ═══════════════════════════════════════════════════════════════════════════════
// SocialAgent — Platform Helpers
// ═══════════════════════════════════════════════════════════════════════════════

if (!function_exists('platformTypes')) {
    /**
     * Human-readable label for a platform type constant.
     * Usage: platformTypes()              → full array
     *        platformTypes(PLATFORM_WHATSAPP) → 'WhatsApp'
     */
    function platformTypes($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => __('Facebook Page'),
            PLATFORM_MESSENGER     => __('Messenger'),
            PLATFORM_WHATSAPP      => __('WhatsApp'),
            PLATFORM_INSTAGRAM     => __('Instagram'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? $input);
    }
}

if (!function_exists('platformIcons')) {
    /**
     * Font Awesome class for a platform type.
     */
    function platformIcons($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => 'fa-brands fa-facebook',
            PLATFORM_MESSENGER     => 'fa-brands fa-facebook-messenger',
            PLATFORM_WHATSAPP      => 'fa-brands fa-whatsapp',
            PLATFORM_INSTAGRAM     => 'fa-brands fa-instagram',
        ];
        return is_null($input) ? $output : ($output[$input] ?? 'fa-solid fa-message');
    }
}

if (!function_exists('platformColors')) {
    /**
     * Brand hex color for each platform type.
     */
    function platformColors($input = null)
    {
        $output = [
            PLATFORM_FACEBOOK_PAGE => '#1877F2',
            PLATFORM_MESSENGER     => '#0084FF',
            PLATFORM_WHATSAPP      => '#25D366',
            PLATFORM_INSTAGRAM     => '#E1306C',
        ];
        return is_null($input) ? $output : ($output[$input] ?? '#6B7280');
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// SocialAgent — Conversation Helpers
// ═══════════════════════════════════════════════════════════════════════════════

if (!function_exists('conversationStatuses')) {
    /**
     * Labels for conversation status constants.
     */
    function conversationStatuses($input = null)
    {
        $output = [
            CONVERSATION_STATUS_OPEN      => __('Open'),
            CONVERSATION_STATUS_RESOLVED  => __('Resolved'),
            CONVERSATION_STATUS_PENDING   => __('Pending'),
            CONVERSATION_STATUS_ESCALATED => __('Escalated'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('conversationStatusBadge')) {
    /**
     * Return an HTML badge span for a conversation status.
     * Used in DataTables columns that need rawColumns.
     */
    function conversationStatusBadge($status)
    {
        $map = [
            CONVERSATION_STATUS_OPEN      => ['label' => __('Open'),      'bg' => '#10b9811a', 'color' => '#10b981'],
            CONVERSATION_STATUS_RESOLVED  => ['label' => __('Resolved'),  'bg' => '#6366f11a', 'color' => '#6366f1'],
            CONVERSATION_STATUS_PENDING   => ['label' => __('Pending'),   'bg' => '#F59E0B1a', 'color' => '#F59E0B'],
            CONVERSATION_STATUS_ESCALATED => ['label' => __('Escalated'), 'bg' => '#ef44441a', 'color' => '#ef4444'],
        ];
        $s = $map[$status] ?? ['label' => __('Unknown'), 'bg' => '#6b72801a', 'color' => '#6b7280'];
        return '<span class="py-4 px-12 bd-ra-50 fs-11 fw-600" style="background:' . $s['bg'] . ';color:' . $s['color'] . ';">'
            . $s['label'] . '</span>';
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// SocialAgent — Keyword Rule Helpers
// ═══════════════════════════════════════════════════════════════════════════════

if (!function_exists('keywordMatchTypes')) {
    /**
     * Labels for keyword match type constants.
     */
    function keywordMatchTypes($input = null)
    {
        $output = [
            KEYWORD_MATCH_CONTAINS    => __('Contains'),
            KEYWORD_MATCH_EXACT       => __('Exact'),
            KEYWORD_MATCH_STARTS_WITH => __('Starts With'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? $input);
    }
}

// ═══════════════════════════════════════════════════════════════════════════════
// SocialAgent — Message Helpers
// ═══════════════════════════════════════════════════════════════════════════════

if (!function_exists('messageSenderTypes')) {
    function messageSenderTypes($input = null)
    {
        $output = [
            MESSAGE_SENDER_CUSTOMER    => __('Customer'),
            MESSAGE_SENDER_AI          => __('AI Agent'),
            MESSAGE_SENDER_HUMAN_ADMIN => __('Agent'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}

if (!function_exists('resolveTemplateVariables')) {
    /**
     * Replace template variables in a reply body.
     *
     * Supported variables:
     *   {customer_name}   — conversation contact name
     *   {business_name}   — app name from settings
     *   {platform}        — platform type label
     */
    function resolveTemplateVariables(string $text, \App\Models\Conversation $conversation): string
    {
        $vars = [
            '{customer_name}' => $conversation->contact_name ?? __('Customer'),
            '{business_name}' => getOption('app_name') ?? config('app.name'),
            '{platform}'      => platformTypes($conversation->platform_type),
        ];

        return str_replace(array_keys($vars), array_values($vars), $text);
    }
}

if (!function_exists('messageStatusLabels')) {
    function messageStatusLabels($input = null)
    {
        $output = [
            MESSAGE_STATUS_SENT      => __('Sent'),
            MESSAGE_STATUS_DELIVERED => __('Delivered'),
            MESSAGE_STATUS_READ      => __('Read'),
            MESSAGE_STATUS_FAILED    => __('Failed'),
        ];
        return is_null($input) ? $output : ($output[$input] ?? __('Unknown'));
    }
}
