/*jshint esversion: 6 */

let lastMarker = null;
let map = null;
let allStopEntries = null;
let allFavInputs = null;
let filterBox = null;


function initStopPage() {
    allStopEntries = document.getElementsByClassName("stopentry");
    allFavInputs = document.getElementsByClassName("favinput");
    filterBox = document.getElementById("filterBox");
    loadStops(() => { }); // initalize stopList variable in util.js, no further processing needed -> empty callback
    const fav = getFavorite();
    if (fav >= 0) {
        const id = "fav-" + fav;
        const input = document.getElementById(id);
        input.checked = true;
    }
    initMap();
}

function updateFilter() {
    let filter = filterBox.value;
    console.log("update filter for " + filter);
    for (let i = 0; i < stopList.length; i++) {
        if (stopList[i].includes(filter)) {
            document.getElementById("stop-" + i).style.display = "flex";
        }
        else {
            document.getElementById("stop-" + i).style.display = "none";
        }
    }
}

function updateFavorite(id) {
    setFavorite(id);
    for (let i = 0; i < allFavInputs.length; i++) {
        const current = allFavInputs.item(i);
        current.checked = false;
    }
    const itemid = "fav-" + id;
    const input = document.getElementById(itemid);
    input.checked = true;
}

function stopSelected(stopId) {
    updatePosition(stopId, position => refocusMap(position));
}

function updatePosition(stopId, callback) {
    load(uniBackendUrl + "stop/" + stopId + "/", stop => {
        callback({ "lat" : parseFloat(stop["lat"]), "lng" : parseFloat(stop["lon"]) });
    }, () =>console.log("could not fetch position"));
}

function initMap() {
    const initialStop = getFavorite();
    updatePosition(initialStop, position => {
        map = new google.maps.Map(document.getElementById('map'), {
            "zoom" : 15,
            "center" : position
        });
        lastMarker = new google.maps.Marker({
            "position" : position,
            "map" : map
        });
    });
}

function refocusMap(location) {
    lastMarker.setMap(null);
    lastMarker = new google.maps.Marker({
        position: location,
        map: map
    });
    map.setCenter(location);
}
