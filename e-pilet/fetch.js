const btn = document.getElementById('checkout-button');

btn.addEventListener('click', (e) => {

    if (typeof stripeLivePublishKey !== 'undefined' && stripeLivePublishKey !== '' && stripeLivePublishKey !== 'string' ) {
 e.preventDefault();
        const stripe = Stripe(stripeLivePublishKey);

    fetch('/wp-content/themes/playroom/stripe/e-pilet-server.php', {

        method: 'POST',
        body: JSON.stringify({
            amount: totalPriceSpanNum,
            children_quantity: childrenQuantity,
            hours_quantity: hoursQuantity,
            open_visitation: openVisitation,
             selected_source: selectedSource,
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    })
      .then(function(response) {
        return response.json();
      })
      .then(function(session) {
        console.log(session);
        return stripe.redirectToCheckout({ sessionId: session.id });
      })
      .then(function(result) {
        if (result.error) {
          alert(result.error.message);
        }
      })
      .catch(function(error) {
        console.log('Fetch Error :-S', error);
      });

} else {
    console.error("Stripe test secret key is not defined or empty.");
}
    });