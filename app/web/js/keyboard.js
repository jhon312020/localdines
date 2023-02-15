(function($) {
  let selectedInput;
  const defaultTheme = "hg-theme-default";
  const Keyboard = window.SimpleKeyboard.default;
  const numKeyboardPos = new Keyboard(".num-keyboard-pos",{
    theme: defaultTheme,
    onChange: input => onChangePos(input),
    onKeyPress: button => onKeyPress(button),
    layout: {
      default: ["1 2 3", "4 5 6", "7 8 9", "{shift} 0 .", "{bksp}"],
      shift: ["! / #", "$ % ^", "& * (", "{shift} ) +", "{bksp}"]
    },
    
  });

  document.querySelectorAll("#paymentModal .input").forEach(input => {  
    // Optional: Use if you want to track input changes
    // made without simple-keyboard
    input.addEventListener("input", onInputChangePos);
    input.addEventListener("focus", (event) => {
      showKeyboard(numKeyboardPos);
      onInputFocusPos(event);
    });
  });

  function onInputChangePos(event) {
    numKeyboardPos.setInput(event.target.value, event.target.id);
  }

  function onChangePos(input) {
    var parentModalName = "#paymentModal";
    document.querySelector(selectedInput || ".input").value = input;
    showBalance(input, parentModalName);
  }

  function onInputFocusPos(event) {
    selectedInput = `#paymentModal #${event.target.id}`;
    numKeyboardPos.setOptions({
      inputName: event.target.id
    });
  }

  const numKeyboardTel = new Keyboard(".num-keyboard-tel",{
    theme: defaultTheme,
    onChange: input => onChangeTel(input),
    onKeyPress: button => onKeyPress(button),
    layout: {
      default: ["1 2 3", "4 5 6", "7 8 9", "{shift} 0 .", "{bksp}"],
      shift: ["! / #", "$ % ^", "& * (", "{shift} ) +", "{bksp}"]
    },
    
  });

  if($("#paymentTelModal").length) {
    document.querySelectorAll("#paymentTelModal .input").forEach(input => {  
      // Optional: Use if you want to track input changes
      // made without simple-keyboard
      input.addEventListener("input", onInputChangeTel);
      input.addEventListener("focus", (event) => {
        showKeyboard(numKeyboardTel);
        onInputFocusTel(event);
      });
    });
  }

  function onInputChangeTel(event) {
    numKeyboardTel.setInput(event.target.value, event.target.id);
  }

  function onChangeTel(input) {
    var parentModalName = "#paymentTelModal";
    document.querySelector(selectedInput || ".input").value = input;
    showBalance(input, parentModalName);
  }

  function onInputFocusTel(event) {
    selectedInput = `#paymentTelModal #${event.target.id}`;
    numKeyboardTel.setOptions({
      inputName: event.target.id
    });
  }

  function onKeyPress(button) {
    console.log("Button pressed", button);
  }
  

  function showKeyboard(keyboardName) {
    keyboardName.replaceInput({payment_modal_pay:''});
    $('.keyboard-container').removeClass('d-none');
  }

  function showBalance(paying, $modalName) {
    var tot = $($modalName+" #payment_modal_tot").text();
    var tot_int = parseFloat(tot);
    var balance;
    var balance_format;
    paying = paying.trim();
    var currency = $($modalName+" #payment_modal_curr").text();
    if(isNaN(paying) || paying == '' ||  parseFloat(paying) < tot_int ) {
      $($modalName+" #error-msg").removeClass("d-none");
      balance_format = currency + " 0.00" ;
      $($modalName+" #paymentBtn").attr("data-valid", "false");
    } else {
      $($modalName+" #error-msg").addClass("d-none");
      balance = parseFloat(paying) - tot_int;
      balance_format = currency +" "+ parseFloat(balance).toFixed(2);
      $($modalName+" #paymentBtn").attr("data-valid", "true");
    }
    $($modalName+" #payment_modal_bal").text(balance_format);
  }
 
})(jQuery);