document.addEventListener('DOMContentLoaded', function () {
    //Carousel
    let carouselItems = document.getElementById('carouselItems');
    let leftBtn = document.getElementById('leftBtn');
    let rightBtn = document.getElementById('rightBtn');
    let scrollAmount = 0;
    let scrollStep = 180; // Approx width of one genre + margin

    leftBtn.addEventListener('click', () => {
      scrollAmount -= scrollStep;
      if (scrollAmount < 0) scrollAmount = 0;
      carouselItems.style.transform = `translateX(-${scrollAmount}px)`;
    });

    rightBtn.addEventListener('click', () => {
      var maxScroll = carouselItems.scrollWidth - carouselItems.parentElement.offsetWidth;
      scrollAmount += scrollStep;
      if (scrollAmount > maxScroll) scrollAmount = maxScroll;
      carouselItems.style.transform = `translateX(-${scrollAmount}px)`;
    });
    
    // map
    var map = L.map('map').setView([46.00, 2.00], 6);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);
    var marker;
    fetch('https://us1.api-bdc.net/data/client-ip')
    .then(res => res.json())
    .then(ipData => {
        const clientIp = ipData.ipString;
        return fetch(`https://api.apibundle.io/ip-lookup?apikey=44ea39fc3150439097ac665447743563&ip=${clientIp}`);
    })
    .then(res => res.json())
    .then(data => {
        marker = L.marker([data.latitude, data.longitude]).addTo(map);
        map.setView([data.latitude, data.longitude], 16);
    })
    .catch(error => {
        console.log(error);
    });
    fetch('https://saevelo.alwaysdata.net/api_request/api.php?action=magasin')
    .then(res => res.json())
    .then(magasins => {
        magasins.forEach(magasin => {
            const address = `${magasin.street}, ${magasin.state}`;

            // Utiliser l'API Nominatim pour géocoder l'adresse
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length > 0) {
                        const latitude = data[0].lat;
                        const longitude = data[0].lon;

                        // Ajouter un marqueur à l'emplacement géocodé
                        const marker = L.marker([latitude, longitude]).addTo(map);
                        marker.bindPopup(`<b>${magasin.store_name}</b><br>${address}`);
                    } else {
                        console.log(`Aucun résultat trouvé pour l'adresse : ${address}`);
                    }
                })
                .catch(error => {
                    console.log(`Erreur lors du géocodage pour l'adresse : ${address}`, error);
                });
        });
    })
    .catch(error => {
        console.log("Erreur lors de la récupération des magasins :", error);
    });

    //Menu dropdown 
    var elems = document.querySelectorAll(".dropdown-trigger");
    var instances = M.Dropdown.init(elems, {
        coverTrigger: false,
        constrainWidth: false,
        hover: false
    });
    
    //filtre
    document.addEventListener('DOMContentLoaded', () => {
        let minSlider = document.getElementById('price-min');
        let maxSlider = document.getElementById('price-max');
        let minOutput = document.getElementById('min-price');
        let maxOutput = document.getElementById('max-price');
      
        let updatePrices = () => {
          let minVal = parseInt(minSlider.value);
          let maxVal = parseInt(maxSlider.value);
      
          if (minVal > maxVal) {
            [minVal, maxVal] = [maxVal, minVal];
          }
      
          minOutput.textContent = `${minVal} €`;
          maxOutput.textContent = `${maxVal} €`;
        };
      
        minSlider.addEventListener('input', updatePrices);
        maxSlider.addEventListener('input', updatePrices);
      
        updatePrices();
      });
      
});