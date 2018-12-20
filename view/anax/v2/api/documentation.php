<?php

namespace Anax\View;

/**
 * Template file to render a view.
 */

// Show incoming variables and view helper functions,
/* echo showEnvironment(get_defined_vars(), get_defined_functions()); */

?><div class="py-4" style="margin-bottom: 150px;">
    <h1>Dokumentation För Röstring <i class="ml-3">Rest API</i></h1>
    <p>
        Detta är en API-tjänst som hanterar röstningar för forumet. Här kan man
        "sätta", "ta bort" och hämta poäng för frågor, svar samt kommentarer.
    </p>
    <p>
        Documentation hittar ni under exempel länkarna.
    </p>
    <!-- Title rows -->
    <h2>Exemplar</h2>
    <h3>Hämta</h3>
    <div class="row d-flex">
        <div class="col">
            <a href="<?= /** @scrutinizer ignore-call */ url("api/get/post/1") ?>">Exepmel</a> när man hämtar poäng för post nummer 1.
        </div>
    </div>
    <div class="d-flex">
    <h3 class="col">Rösta & Hämta</h2>
    <h3 class="col">Om fel uppstår</h2>
    </div>
    <!-- row -->
    <div class="row d-flex">
        <div class="col">
            <a href="<?= /** @scrutinizer ignore-call */ url("api/vote/doe/doe/like/post/1?methods=both") ?>">Exepmel</a> like på post nummer 1.
        </div>
        <div class="col">
            <a href="<?= /** @scrutinizer ignore-call */ url("api/get/") ?>">Exepmel</a> på ett felmedelande.
        </div>
    </div>





    <!--  Documentation  -->

    <h2>Request Parametrar</h2>
    <p>För att hämta poäng:</p>
    <ol class="noDec pb-4">
        <li><b>Username </b><small class="text-secondary">required,</small><small> ditt användarnamn</small></li>
        <li><b>Password </b><small class="text-secondary">required,</small><small> ditt lösenord</small></li>
        <li><b>Action</b> <small class="text-secondary">required</small> <small>form av röstning:</small>
            <ul>
                <li>"like" - <small class="text-secondary">+1</small></li>
                <li>"dislike" - <small class="text-secondary">-1</small></li>
            </ul>
        </li>
        <li><b>Type </b><small class="text-secondary">required,</small><small> säger typen av inlägget:</small>
            <ul>
                <li>"comment" - <small class="text-secondary">För kommentarer</small></li>
                <li>"post" - <small class="text-secondary">För frågor eller svar</small></li>
            </ul>
        </li>
        <li><b>Id</b> <small class="text-secondary">required,</small> <small>inläggets id nummer</small></li>
    </ol>
    <p>Vill man både rösta och hämta det nya värdet kan man lägga till en optionell parameter. Genom att sätta
        <i><code>methods=both</code></i> skickar du en request för att uppdatera poängen och hämta den nya.</p>
    <ol class="noDec">
        <li><b>Methods </b><small class="text-secondary">optionell,</small><small> <ul><li>both</li></ul></small></li>
    </ol>
    Optionella parametrar läggs till som en <a href="https://en.wikipedia.org/wiki/Query_string">query</a> mot slutet av strängen, ex. <code>/.../[id]?methods=both</code></i></small>

    <h2>API</h2>
    <h3>Användning</h3>
    <small>Alla felmedelanded returnerar <code>[ error: message ]</code></small>
    <hr class="clearfix">
    <h4>GET</h4>
    <p>För att rösta (och hämta resultat för den) <b>första frågan</b>.</p>
    <pre class="hljs text-left"><span class="hljs-keyword">GET</span> /api/vote/[username]/[password]/[action]/[type]/[id]?[optional]<br><b>GET</b> /api/vote/doe/doe/like/post/1?methods=both</pre>
    <i>Resultat</i>
    <pre class="hljs text-left">[
    points: 5
    <span class="hljs-comment">Returnerar i form av en <i>Integer</i></span>
]
</pre>
    <hr class="clearfix">
    <h4>GET</h4>
    <p>För att hämta resultat för den <b>första frågan</b>.</p>
    <pre class="hljs text-left"><span class="hljs-keyword">GET</span> /api/get/[type]/[id]<br><b>GET</b> /api/get/post/1</pre>
    <i>Resultat</i>

    <pre class="hljs text-left">[
    status: "done"
    <span class="hljs-comment">Man får samma respons om man ändas röstar</span>
]</pre>
</div>



<!-- <pre class="hljs text-left">[
    current: [
        latitude: 56.1621073,
        longitude: 15.5866422,
        timezone: "Europe/Stockholm",
        daily: [
            summary: "Ingen mätbar nederbörd u…ill 5°C under onsdagen.",
            icon: "clear-day",
            0: [
                time: 1542409200,
                summary: "Molnigt under dagen.",
                icon: "partly-cloudy-day",
                sunriseTime: 1542436832,
                sunsetTime: 1542466053,
                moonPhase: 0.31,
                precipIntensity: 0,
                precipIntensityMax: 0.0025,
                precipIntensityMaxTime: 1542423600,
                precipProbability: 0,
                temperatureHigh: 5.44,
                temperatureHighTime: 1542434400,
                temperatureLow: 2.93,
                temperatureLowTime: 1542492000,
                apparentTemperatureHigh: 5.44,
                apparentTemperatureHighTime: 1542434400,
                apparentTemperatureLow: -0.22,
                apparentTemperatureLowTime: 1542495600,
                dewPoint: 3.03,
                humidity: 0.87,
                pressure: 1040.8,
                windSpeed: 1.16,
                windGust: 5.56,
                windGustTime: 1542492000
                windBearing: 295,
                cloudCover: 0.74,
                uvIndex: 0,
                uvIndexTime: 1542409200,
                visibility: 14.4,
                ozone: 280.04,
                temperatureMin: 2.93,
                temperatureMinTime: 1542492000,
                temperatureMax: 6.44,
                temperatureMaxTime: 1542409200,
                apparentTemperatureMin: -0.19,
                apparentTemperatureMinTime: 1542492000,
                apparentTemperatureMax: 5.44,
                apparentTemperatureMaxTime: 1542430800
            ],
            ...
            7: [
                <span class="hljs-comment">Samma innehåll som ["daily"][0]</span>
            ]
        ],
        offset: 1
    ],
    previous: [
        0: [
            0: [
                <span class="hljs-comment">Samma som current["daily"]</span>
                <span class="hljs-comment">Denna arrayen kommer inte att synas om days = 0</span>
            ]
        ]
        1: [
            0: [
                ....
            ]
        ]
        ...
    ]
]</pre> -->