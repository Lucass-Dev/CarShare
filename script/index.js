
function loadScript(name){
    const prev = document.getElementById('dynamic-script');
    if(prev) prev.remove();

    const s = document.createElement('script');
    s.src = `./script/${name}.js`;
    s.defer = true;
    s.id = 'dynamic-script';
    s.onerror = (e) => {
        console.error(`[loader] failed to load ${name}.js`, e);
    };
    document.head.appendChild(s);
}

function loadCSS(name){
    const prev = document.getElementById('dynamic-css');
    if (prev) prev.remove();

    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = `./assets/styles/${name}.css`;
    link.id = 'dynamic-css';

    link.onload = () => {
        console.log(`[loader] ${name}.css loaded`);
    };

    link.onerror = (e) => {
        console.error(`[loader] failed to load ${name}.css`, e);
    };

    document.head.appendChild(link);
}

const params = new URLSearchParams(window.location.search);

if (params.get("controller") == null) {
    loadCSS("home");
    loadScript("trip");
}else{
    loadScript(params.get("controller"));
    loadCSS(params.get("controller"));
}
