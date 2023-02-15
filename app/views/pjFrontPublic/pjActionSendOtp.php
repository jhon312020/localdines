
<div class="fdLoader"></div>
<?php
//echo $_SESSION['otp'];
$index = $controller->_get->toString('index');
$CLIENT = $controller->isFrontLogged() ? @$_SESSION[$controller->defaultClient] : array();
?>
<br />
<div class="container">
  <div class="row">
	  <div id="fdMain_<?php echo $index; ?>" class="col-md-8 col-sm-8 col-xs-12 pjFdPanelLeft mt-mob" style= "margin-left: auto;margin-right: auto;">
		  <div class="panel panel-default">
			<?php //include_once dirname(__FILE__) . '/elements/header.php';?>
			  <div class="panel-body  pjFdPanelBody">
          <h3 class="text-center">Please Enter OTP to continue!!</h3>
          <form id="fdOtpForm_<?php echo $index;?>" method="POST" data-group-name="digits" autocomplete="off" action="">
            <div class="digit-group">
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-1" name="digit-1" data-next="digit-2" size="1" maxlength="1" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" size="1" maxlength="1" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" size="1" maxlength="1" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" size="1" maxlength="1" />
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-5" name="digit-5" data-next="digit-6" data-previous="digit-4" size="1" maxlength="1"/>
              <input type="number" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="digit-6" name="digit-6" data-previous="digit-5" size="1" maxlength="1" />
            </div>
              <div style="margin-top: 20px;display: none;text-align: center" id="otpErr"><span class="text-danger">Please enter a valid OTP number</span></div>
              <div style="margin-top: 20px;display: none;text-align: center" id="otpWrong"><span class="text-danger">OTP is invalid</span></div>
              <?php   if(isset($_GET['msg_credit_err']) && $_GET['msg_credit_err'] == 1){ ?>
                  <div style="margin-top: 20px;"><span class="text-danger"><?php echo "Please contact our administrator!!";?></span></div>
              <?php }
              ?>
              <div id="otp-btns">
                <!--  <a href="review.php?resendOtp=1">Resend OTP?</a> -->
                <button role="button" name="otpResendBtn" class="btn-otp fdButtonOtpResend" style="margin-bottom: 10px;">Resend Otp?</button>
                <button role="submit" name="otpSubmitBtn" class="btn-otp fdButtonOtp" style="margin-bottom: 10px;float: right;">Submit</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--elements end-->
<!-- Preview Modal -->
<!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#previewModal">
    Open modal
  </button> -->
<div class="modal" id="previewModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">PREVIEW</h4>
          <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
        </div>
        <!-- Modal footer -->
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div> -->
      </div>
    </div>
  </div>
<!-- End of Preview Modal -->
<script type="text/javascript">
console.log(<?php echo $_SESSION['otp']; ?>)
document.querySelectorAll('.digit-group input').forEach(item => {
  item.addEventListener('keyup', event => {
    var parent = document.querySelector('.digit-group');
    if (event.keyCode === 8 || event.keyCode == 37) {
      var prev = document.getElementById(item.getAttribute('data-previous'));
      try {
        prev.select();
      } catch (e) {
        return;
      }
    } else if (item.value >= 0) {
      var next = document.getElementById(item.getAttribute('data-next'));
      try {
        next.select();
      } catch (e) {
        return;
      }
    }
  })
})
$(function() {
	if($(".food-item-desc").length > 0) {
		$(".search-me").css("display","block");
	} else {
		$(".search-me").css("display","none");
    if ($("#searchInput-group").css("display") == "flex") {
			$("#searchInput-group").css("display", "none");
      $(".logo").css("display", "block");
		}
	}
})
$(".close-modal").click(function() {
	$("#previewModal").modal("hide"); 
})
</script>


