(function($) {
  KioskBoard.init({keysJsonUrl: '/app/web/js/kioskboard/kioskboard-keys-english.json', capsLockActive: false, keysNumpadArrayOfNumbers: [1, 2, 3, 4, 5, 6, 7, 8, 9, 0, "."],});
  KioskBoard.run('.js-kioskboard-input');
})(jQuery);