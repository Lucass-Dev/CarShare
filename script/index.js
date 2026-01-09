function loadScript(name){
    // remove previous dynamic script if present
    const prev = document.getElementById('dynamic-script');
    if(prev) prev.remove();

    const s = document.createElement('script');
    s.src = `./script/${name}.js`;
    s.defer = true;
    s.id = 'dynamic-script';
    s.onload = () => {
        // utile pour debug (ou init après chargement)
        console.log(`[loader] ${name}.js loaded`);
    };
    s.onerror = (e) => {
        console.error(`[loader] failed to load ${name}.js`, e);
    };
    document.head.appendChild(s);
}

const params = new URLSearchParams(window.location.search);
switch (params.get("controller")) {
    case "trip":
        switch (params.get("action")) {
            case "display_search":
            case "search":
                loadScript("searchPage");
                break;
            default:
                break;
        }
        break;
    default:
        break;
}