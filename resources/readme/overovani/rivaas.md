# Rivaas

1) začátek ověřování
  
  auth/ext/rivaas/verify-begin provede přesměrování na:
  \App\Services\Auth\Ext\RivaasService::getAuthUrl()

2) auth/ext/rivaas/callback
   - pokud je ověření validní, ta se sem pošlou data z ověření, kde se zpracují a uloží 


3) auth/ext/rivaas/verified
   - sem je přesměrováno pokud je ověření validní
   - pokud validní není tak se směřuje na rejected nebo unverified
