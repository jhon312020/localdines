var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function($, undefined) {
  //var Keyboard = window.SimpleKeyboard.default;

  let selectedInput;
  const defaultTheme = "hg-theme-default hg-layout-default myTheme";
  const Keyboard = window.SimpleKeyboard.default;
  






  $(document).ready(function() {

    $(".V_pos").click(function() {
      let keyboard1 = new Keyboard(".keyboard1", {
        onChange: input => onChange_pos(input),
        onKeyPress: button => onKeyPress_pos(button)
      });
      document.querySelectorAll(".v_pos").forEach(input => {
        input.addEventListener("focus", onInputFocus_pos);
        input.addEventListener("input", onInputChange_pos);
      });

      function onInputFocus_pos(event) {
        //selectedInput = `#${event.target.id}`;
        keyboard1.setOptions({
          inputName: $(".V_pos").val()
        });
      }

      function onInputChange_pos(event) {
        keyboard1.setInput(event.target.value);
      }

      function onChange_pos(input) {
        console.log("Input changed", input);
        document.querySelector(".V_pos ").value = input;
      }

      function onKeyPress_pos(button) {
        console.log("Button pressed", button);
        if (button === "{lock}" || button === "{shift}") handleShiftButton_pos();
      }

      function handleShiftButton_pos() {
        let currentLayout = keyboard1.options.layoutName;
        let shiftToggle = currentLayout === "default" ? "shift" : "default";
        keyboard1.setOptions({
          layoutName: shiftToggle
        });
      }
      $(".V_pos").unbind();

    });

    /*$(".close_pos").click(function() {
      $(".V_pos").unbind();
    });*/


    $(".V_tel").click(function() {
      let keyboard2 = new Keyboard(".keyboard2", {
        onChange: input => onChange_tel(input),
        onKeyPress: button => onKeyPress_tel(button),
      });
      document.querySelectorAll(".v_pos").forEach(input => {
        input.addEventListener("focus", onInputFocus_tel);
        input.addEventListener("input", onInputChange_tel);
      });

      function onInputFocus_tel(event) {
        //selectedInput = `#${event.target.id}`;
        keyboard2.setOptions({
          inputName: $(".V_tel").val()
        });
      }

      function onInputChange_tel(event) {
        keyboard2.setInput(event.target.value);
      }

      function onChange_tel(input) {
        console.log("Input changed", input);
        document.querySelector(".V_tel").value = input;
      }

      function onKeyPress_tel(button) {
        console.log("Button pressed", button);
        if (button === "{lock}" || button === "{shift}") handleShiftButton_pos();
      }

      function handleShiftButton_tel() {
        let currentLayout = keyboard2.options.layoutName;
        let shiftToggle = currentLayout === "default" ? "shift" : "default";
        keyboard2.setOptions({
          layoutName: shiftToggle
        });
      }
      $(".V_tel").unbind();

    });
    /*$(".close_tel").click(function() {
      $(".V_tel").unbind();
    });*/

    $("#client-tab").click(function() {
      let selectedInput;

      let keyboard3 = new Keyboard(".keyboard3", {
        onChange: input => onChange(input),
        onKeyPress: button => onKeyPress(button)
      });
      document.querySelectorAll(".input").forEach(input => {
        input.addEventListener("focus", onInputFocus);
        input.addEventListener("input", onInputChange);
      });

      function onInputFocus(event) {
        selectedInput = `#${event.target.id}`;
        keyboard3.setOptions({
          inputName: event.target.id
        });
      }

      function onInputChange_tel(event) {
        keyboard2.setInput(event.target.value);
      }

      function onInputChange(event) {
        keyboard3.setInput(event.target.value, event.target.id);
      }

      function onChange(input) {
        console.log("Input changed", input);
        document.querySelector(selectedInput || ".input").value = input;
      }

      function onKeyPress(button) {
        console.log("Button pressed", button);
        if (button === "{lock}" || button === "{shift}") handleShiftButton();
      }

      function handleShiftButton() {
        let currentLayout = keyboard3.options.layoutName;
        let shiftToggle = currentLayout === "default" ? "shift" : "default";
        keyboard3.setOptions({
          layoutName: shiftToggle
        });
      }
      $("#client-tab").unbind();

    });
    /* $(".close_t").click(function() {
       $("#client-tab").unbind();
     });*/

    $(".phn_no").click(function() {
      //let selectedInput;
      let keyboard4 = new Keyboard(".keyboard4", {
        onChange: input => onChange_pno(input),
        onKeyPress: button => onKeyPress_pno(button),
        theme: "hg-theme-default hg-layout-default myTheme",
        layout: {
          default: [
            "1 2 3",
            "4 5 6",
            "7 8 9",
            "0",
            "{bksp}",
          ]
        }
      });
      document.querySelectorAll(".phn_no").forEach(input => {
        input.addEventListener("focus", onInputFocus_pno);
        input.addEventListener("input", onInputChange_pno);
      });

      function onInputFocus_pno(event) {
        // selectedInput = `#${event.target.id}`;
        keyboard4.setOptions({
          inputName: $(".phn_no").val()
        });
      }

      function onInputChange_pno(event) {
        keyboard4.setInput(event.target.value);
      }

      function onChange_pno(input) {
        console.log("Input changed", input);
        document.querySelector(".phn_no").value = input;
        getClientInfo($(".phn_no"));
        validatePhoneNumber(input);
      }

      function onKeyPress_pno(button) {
        console.log("Button pressed", button);
      }
      $(".phn_no").unbind();
    });
    /* $(".close_p").click(function() {
       $(".phn_no").unbind();
       $(".add_phn").addClass("d-none");
     });*/
  });
  $(document).on("click", ".close_p", function() {
    $(".add_phn").addClass("d-none");
  })
  $(document).on("click", ".phn_no", function() {
    $(".add_phn").removeClass("d-none");
  })
  $(document).on("click", ".cd1,.cd2,.cd3,.cd4,.cd5,.cd6,.cd7,.cd8,.cd9,.cd10,.cd11,.cd12", function() {
    $(".voucher_cl").removeClass("d-none");
  })
  $(document).on("click", ".V_pos", function() {
    $(".voucher_pos").removeClass("d-none");
  })
  $(document).on("click", ".V_tel", function() {
    $(".voucher_tel").removeClass("d-none");
  })
  $(document).on("click ", ".close_pos ", function() {
    $(".voucher_pos").addClass("d-none");
  })
  $(document).on("click ", ".close_tel ", function() {
    $(".voucher_tel").addClass("d-none");
  })
  $(document).on("click", ".close_t", function() {
    $(".voucher_t").addClass("d-none");
  })


  $("#phone_no,#c_email,#c_name,#c_surname,#p_time,#d_notes,#inputPostCode,#d_address_1,#d_time,#d_address_1,#d_address_2,#delivery_fee,#d_city").click(function(e) {
    if(this.tagName == 'INPUT') {var t_value = 85;}
    if(this.tagName == 'TEXTAREA') {var t_value = 68;}
    //if($(this).("tagName") === 'input'){var t_value = 85;}
    //if($(this).prop("tagName") === 'textarea'){var t_value = 91;}
    const val = $(this).offset().top;
    const top = val - t_value;

    console.log("t1", val);
    $(".add_phn").css({
      "width": "32%",
      "position": "absolute",
      "z-index": "1000",
      "top": top
    });
    
    console.log(top);
    $(".voucher_t").css({
      "width": "100%",
      "position": "absolute",
      "z-index": "1000",
      "top": top
    });


  });


  function getClientInfo($this) {
    console.log('came here');
            $c_phone = $this.val();
              $cinfo = $.ajax({
                type: "POST",
                async: false,
                url: "index.php?controller=pjAdminPosOrders&action=pjActionCheckClientPhoneNumber",
                data: { 
                  value: function() {
                    return $c_phone;
                  }
                },
            });
            if($cinfo.responseText == 'new user') {
              $("#c_title").val("mr");
              $("#c_email").val("");
              $("#c_email").attr('data-wt','invalid');
              //$("#c_surname").val("");
              $("#inputPostCode").val("");
              $("#d_address_1").val("");
              $("#d_address_2").val("");
              $("#d_city").val("");
              //$("#c_name").val("");
              $("#mobile_delivery_info_yes").prop("checked",true);
              $("#mobile_offer_yes").prop("checked",false);
              $("#email_receipt_yes").prop("checked",true);
              $("#email_offer_yes").prop("checked",false);
              $('#jsEmailOffer').css("display","none");
            } else {
              var c_arr = $cinfo.responseJSON[0];
              $("#c_title").val(c_arr.c_title);
              //c_arr.email == '0' ? $("#c_email").val("") : ;
              if (c_arr.email == '0') {
                $("#c_email").val("");
              } else {
                $("#c_email").val(c_arr.email);
                $('#jsEmailOffer').css("display","block");
                c_arr.email_offer == 1 ? $("#email_offer_yes").prop("checked",true) : $("#email_offer_no").prop("checked",true);
                $('[name=email_offer]').attr("data-wt","valid");
              }
              if ($("#c_name").length > 0 || $("#c_surname").length > 0 ) {
                $("#c_name").removeClass("required");
                $("#c_surname").removeClass("required");
              }
              if (c_arr.status == 'F') {
                swal({
                  title: "Account Disabled!",
                  text: "The Client account has been disabled...",
                  type: "warning",
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "OK",
                  closeOnConfirm: true,
                },function () {
                });
              }
              //c_arr.sms_email == '' ? $("#c_email").attr('data-wt','invalid') : $("#c_email").attr('data-wt','valid');
              $("#c_surname").val(c_arr.u_surname);
              $("#inputPostCode").val(c_arr.c_postcode);
              if (c_arr.c_postcode) {
                $("#inputPostCode").attr('data-wt','valid');
              }
              $("#d_address_1").val(c_arr.c_address_1);
              $("#d_address_2").val(c_arr.c_address_2);
              $("#d_city").val(c_arr.c_city);
              $("#c_name").val(c_arr.name);
              c_arr.email_delivery_info == 1 ? $("#email_delivery_info_yes").prop("checked",true) : $("#email_delivery_info_no").prop("checked",true);
              c_arr.email_receipt == 1 ? $("#email_receipt_yes").prop("checked",true) : $("#email_receipt_no").prop("checked",true);
              c_arr.mobile_delivery_info == 1 ? $("#mobile_delivery_info_yes").prop("checked",true) : $("#mobile_delivery_info_no").prop("checked",true); 
              c_arr.mobile_offer == 1 ? $("#mobile_offer_yes").prop("checked",true) : $("#mobile_offer_no").prop("checked",true);
              }  
          }
  function validatePhoneNumber(data) {
    console.log('called me phonenuumber');
    var ph = data;
    ph = $.trim(ph);
    var len = ph.toString().length;
    if (len == 11 && isNaN(ph) == false) {
      $("#phone_no").attr("data-wt","valid");
    } else {
      $("#phone_no").attr("data-wt","invalid");
    }
  }

})(jQuery_1_8_2);