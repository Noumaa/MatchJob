var input = document.getElementById("adress-choice");
var datalist = document.getElementById("adress-suggestions");
var cp = document.getElementById("adress-cp");
var city = document.getElementById("adress-city");
var region = document.getElementById("adress-region");
var departement = document.getElementById("adress-department");
// Utilisation de la fonction debounce pour limiter les appels à l'API
var debounceSearchAddress = debounce(searchAddress, 2000);
// Ajout d'un écouteur d'événement sur l'input pour appeler la fonction debounceSearchAddress à chaque fois que l'utilisateur tape une lettre
input.addEventListener("input", debounceSearchAddress);


/**
 * Cette fonction prend une fonction de rappel et un délai en millisecondes en entrée. 
 * Elle renvoie une nouvelle fonction qui peut être appelée répétitivement, mais exécute 
 * la fonction de rappel seulement après que l'utilisateur arrête d'appeler la fonction 
 * pendant le temps spécifié dans le délai.
 * @param {function} callback - La fonction de rappel à exécuter.
 * @param {number} delay - Le délai en millisecondes pour lequel l'exécution de la fonction est retardée.
 * @returns {function} - La nouvelle fonction qui retarde l'exécution de la fonction de rappel.
 */
function debounce(callback, delay) {
  var timer;
  return function () {
    var args = arguments;
    var context = this;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, delay)
  }
}

/**
 * Cette fonction permet de vider les options d'un datalist en supprimant son contenu HTML.
 * @param {HTMLElement} datalist - L'élément datalist à vider.
 */
function clearDatalist(datalist) {
  datalist.innerHTML = '';
}

/**
 * Récupère les informations de l'adresse sélectionnée dans la liste déroulante et les affiche dans les champs correspondants.
 * @param {object} event - L'événement input déclenché sur l'input de recherche d'adresse.
 */
function addValueInput(event) {
  const selectedAddress = event.target.value;
  let options = document.getElementsByTagName("option");
  for (var i = 0; i < options.length; i++) {
    if (options[i].value === selectedAddress) 
    {
      cp.value = options[i].attributes[1].nodeValue;
      city.value = options[i].attributes[2].nodeValue;
      region.value = options[i].attributes[4].nodeValue;
      departement.value = options[i].attributes[3].nodeValue;
    }
  }
}

/**
 * Effectue une recherche d'adresse en appelant l'API de géocodage de la France.
 * 
 * @param {string} value - La valeur à chercher.
 * @param {HTMLInputElement} input - L'élément HTML de l'input contenant la valeur à chercher.
 * @param {HTMLDataListElement} datalist - L'élément HTML de la liste de suggestions.
 */
function searchAddress() 
{
  if (input.value.length < 5) { // vérifier la longueur de la valeur ici
    return;
  }

  input.addEventListener("input", debounceSearchAddress); // définir l'écouteur d'événement ici
  let url = `https://api-adresse.data.gouv.fr/search/?q=${input.value}&type=housenumber&limit=15&autocomplete=1`
  fetch(url)
    .then(response => response.json())
    .then(data => {

      if (data.features.length == 0) {
        datalist.innerHTML = '';
        const option = document.createElement('option');
        option.value = 'Aucun résultat trouvé.';
        datalist.appendChild(option);
      }
      else {
        clearDatalist(datalist)
        data.features.forEach(feature => {
          const option = document.createElement('option');
          option.value = feature.properties.label;
          option.setAttribute("postcode", feature.properties.postcode);
          option.setAttribute("city", feature.properties.city);
          let context = feature.properties.context.split(", ");
          option.setAttribute("departement", context[1]);
          option.setAttribute("region", context[2]);

          datalist.appendChild(option);
        });
      }
    })
    .catch(error => console.log(error));


  // Indication de la valeur selectionné 
  input.addEventListener('change', (event) => {
    addValueInput(event);
  });
}




