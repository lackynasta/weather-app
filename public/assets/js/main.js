$(document).ready(function () {
  $(".slides").slick({
    arrows: true,
    infinite: true,
    dots: true,
    autoplay: true
  });

  $('.select-city').on('change', function () {
    window.location = $(this).val();
  })
});
