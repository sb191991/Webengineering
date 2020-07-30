/*jshint esversion: 6 */

//extract elements for index page
let startBox = null;
let endBox = null;
let favname = null;
let favdepartures = null;
let stopOptions = null;
let routeResult = null;

function initData() {
    startBox = document.getElementById("start");
    endBox = document.getElementById("end");
    favname = document.getElementById("favname");
    favdepartures = document.getElementById("favdepartures");
    stopOptions = document.getElementById("stops");
    routeResult = document.getElementById("result");
    loadFavorite();
    loadStops(updateDepartures);
}

function loadFavorite() {
    const fav = getFavorite();
    load(uniBackendUrl + "stop/" + fav + "/", stop => {
        favname.innerHTML = "Favorit: " + stop.name;
    }, () => console.log("error: could not load favorite stop"));
}

function updateDepartures() {
    const fav = getFavorite();
    load(uniBackendUrl + "stop/" + fav + "/departure/", departures => {
        favdepartures.innerHTML = "";
        departures.forEach( currentValue =>
            favdepartures.innerHTML += "<li>" + currentValue.time + " Linie " + currentValue.display + " (" + stopList[parseInt(currentValue.heading)] + ")</li>"
        );
        setTimeout(updateDepartures, 60000);
    }, () => console.log("error: could not load favorite departures"));
}

function findRoute() {
    const start = startBox.value;
    const end = endBox.value;
    let anyError = false;
    const startId = toId(start);
    const endId = toId(end);
    if (startId == -1) {
        startBox.style.borderColor = "red";
        anyError = true;
    }
    else {
        startBox.style.borderColor = "";
    }
    if (endId == -1) {
        endBox.style.borderColor = "red";
        anyError = true;
    }
    else {
        endBox.style.borderColor = "";
    }
    if (!anyError) {
        //console.log(backendUrl + "search.php?s=" + startId + "&e=" + endId);
        load("search.php?s=" + startId + "&e=" + endId, replyObject => {
            console.log(replyObject);
            resetResult();
            routeResult.innerHTML += "<button type=\"reset\" id=\"closebutton\" onClick=\"resetResult()\">X</button>";
            if (replyObject.type != "success") {
                displayError(replyObject.msg);
            }
            else {
                const routes = replyObject.msg.route;
                let tmp = "<ul id=\"resultlist\">";
                console.log("routes: "+JSON.stringify(routes));
                console.log("route length: "+routes.length);
                let stopCount = 0;
                // loop over all routes (line change -> new route)
                routes.forEach(function (route) {
                    console.log("route: "+JSON.stringify(route));
                    stopCount += route.trip.length;
                    const lineDisplay = route.display;
                    const lineHeading = stopList[parseInt(route.heading)];
                    let firstTime = route.trip[0].time;
                    let firstStop = stopList[parseInt(route.trip[0].stop)];
                    const lastTime = route.trip[route.trip.length - 1].time;
                    const lastStop = stopList[parseInt(route.trip[route.trip.length - 1].stop)];
                    tmp += "<li>Linie " + lineDisplay + " (" + lineHeading + "): " + firstStop + " (" + firstTime + ") -> " + lastStop + " (" + lastTime + ")</li>";
                });
                tmp += "</ul>";
                routeResult.innerHTML += tmp;
                console.log(routeResult.innerHTML);
            }
        }, () => console.log("error: could not load route plan"));
    }
}


function displayError(msg) {
    routeResult.innerHTML += msg;
}

function resetResult() {
    routeResult.innerHTML = "";
}

function getDate(datestring) {
    const split = datestring.split(":");
    const current = new Date();
    return new Date(current.getFullYear(), current.getMonth(), current.getDate(), parseInt(split[0]), parseInt(split[1]));
}
