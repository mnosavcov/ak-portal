# Bank iD

nastavení parametrů pro ověřování
[https://developer.bankid.cz/organizations/pv_trusted_s_r_o_](https://developer.bankid.cz/organizations/pv_trusted_s_r_o_)

- Initiate Login URI
    - použije se pro odkaz "zpět" z ověřování

- Redirect URIs
    - stránka na kterou se vrátí po dokončení ověřování

- Notification URI
    - URL pro odeslání notifikací od autorizační autority (posílá se po změně údajů ze strany klienta a je potřeba je
      zpracovat a použít pro změny)

## postup

1) po kliknutí na ověření se přesměruje na stránku bankid pomocí odkazu vytvořeným funkcí:
   \App\Services\Auth\Ext\BankIdService::getAuthUrl()
    - auth/ext/bankid/verify-begin provede přesměrování na výše uvedený odkaz

2) po návratu na "Redirect URIs"
    - po návratu se otevře view('profile.verify.bankid')
        - tady se z URL odebere část za # ze které se použije "access_token" a zavolá se
          /auth/ext/bankid/profile ve kterém se zavolá služba na bankid, z toho se vyextrahují data, uloží do DB a vrátí
          se zpět hodnoty do stránky a je možné pokračovat ve verifikaci
