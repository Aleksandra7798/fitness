<?php

namespace models;


abstract class RequirementEnum
{
    //typ treningu
    const GRUPOWY = "Grupowy";
    const INDYWIDUALNY   = "Indywidualny";

    //zajecia treningowe
    const JOGA = "Joga";
    const PILATES = "Pilates";
    const ZUMBA = "Zumba";
    const TRENING_OBWODOWY = "Trening obwodowy";
    const BODY_PUMP = "Body pump";
    const FITBALL = "FitBall";
    const AEROBIK = "Aerobik";
    const AQUA_AEROBIK = "Aqua Aerobik";
    const ZDROWY_KREGOSLUP = "Zdrowy kręgosłup";
    const FITBOXING = "FitBoxing";
    const KALISTENIKA = "Kalistenika";
    const BRZUCH_STRETCH = "Brzuch + stretch";

    //dodatkowe uslugi
    const JEDZENIE_NAPOJE = "Jedzenie/napoje";
    const SZATNIA = "Szatnia";
    const MASAZ = "Masaż";
    const SAUNA = "Sauna";
    const BASEN = "Basen";
}
