## .ENV

.env nurodome path kur exportuoti failus:

EXPORT_PATH=

## 1. Languages

Įsivedame kalbas.

## 2. Projects

Įsivedame projektus.

## 3. Scan

Nuskanuojam visus translation failus iš nurodytų projektų. 

## 4. Export

Pasirinkus kalbą, galima exsportuoti turimus sources ir tos kalbos translationus į .xlsx formato faila, kuris susikurs .ENV EXPORT_PATH nurodytoje direktorijoje. Failo formatas:

1. Source id;
2. Source key arba jeigu egzistuoja, en-US translation. Pastaba: en-US exsporte imamas source key, kitose kalbose, ieškome en-US translationo.
3. Translationas, jei toks egzistuoja.

Visų turimų projektų sourcai importuojami į viena excel failą. Vėliau juos galime atskirti pagal source id.

## 5. Import

Pasirinkus kalba, galima importuoti translationus. Priimamas .xlsx formato failas identišku formatu kaip ir exsportinime.

## 6. Translations

Šiame tabe yra pagal projektą ir kalbą išrūšiuoti sources ir jų translationai. Galima juos editinti ir rankiniu būdu. 

Išsaugojus arba importavus, translationai, kurie dar nepublishinti, pasidaro zali. 

Publish translations mygtukas - perrašo visų projektų json failus. Kiekvienam projekte - tik to projekto vertimai. 

