//Function to show/hide details by clicking a button
function showMore() {
    var x = document.getElementById("hidden");
    document.getElementById('reveal-text').innerHTML = x.style.display == "none" ? "Show less text" : "Show more text" /*Nihal helped */

    if (x.style.display == "none") {
      x.style.display = "block";
    } else {
      x.style.display = "none";
    }
  }


  