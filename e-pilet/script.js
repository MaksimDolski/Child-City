 const stripe = Stripe(stripeLivePublishKey);

    jQuery('#product-btn').on('click', function() {
        var amount = parseInt(jQuery('#total-price-all').text());
      fetch('/wp-content/themes/playroom/stripe/server.php', {
        method: 'POST',
        body: JSON.stringify({
            amount: amount,
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
    });