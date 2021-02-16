const formIds = {
  register: '#registration-form',
  login: '#login-form',
  logout: '#sign-out-link',
  reservation: '#reservation-form',
  updateProfile: '#update-profile-form'
};

const formData = {
  registration: function () {
    return {
      fullName: $('input[name="registrationFullName"]').val(),
      phoneNumber: $("input[name='registrationPhoneNumber']").val(),
      email: $("input[name='registrationEmail']").val(),
      password: $("input[name='registrationPassword']").val(),
      password2: $("input[name='registrationPassword2']").val(),
      submitBtn: $('input[name="registerSubmitBtn"]').val() 

    };
  },
  login: function () {
    return {
      email: $('input[name="loginEmail"]').val(),
      password: $('input[name="loginPassword"]').val(),
      submitBtn: $('input[name="loginSubmitBtn"]').val()
    };
  },
  reservation: function () {
    return {
      cid: $('input[name="cid"][isForTest="false"]').val(),
      start: $('input[name="startDate"][isForTest="false"]').val(),
      end: $('input[name="endDate"][isForTest="false"]').val(),
      type: $('select[name="treningType"][isForTest="false"]').val(),
      requirement: $('select[name="treningRequirement"][isForTest="false"]').val(),
      cadre: $('select[name="cadre"][isForTest="false"]').val(),
      service: $('select[name="service"][isForTest="false"]').val(),
      memo: $('textarea[name="specialMemo"][isForTest="false"]').val(),
      submitBtn: $('input[name="reservationSubmitBtn"]').val()
    };
  },
  updateProfile: function () {
    return {
      cid: $('input[name="customerId"]').val(),
      fullName: $('input[name="updateFullName"]').val(),
      newPhone: $("input[name='updatePhoneNumber']").val(),
      email: $("input[name='updateEmail']").val(),
      newPassword: $("input[name='updatePassword']").val(),
      submitBtn: $('input[name="updateProfileSubmitBtn"]').val()
    };
  }
};

const registrationSubmit = function () {
  let registrationData = formData.registration();
  $.ajax({
    url: 'app/process_registration.php',
    type: 'post',
    data: registrationData
  }).done(function (response) {
    $(formIds.register).find('.alert').remove();
    $(formIds.register).prepend(response);
  });
};

const loginSubmit = function () {
  let loginData = formData.login();
  $.ajax({
    url: 'app/process_login.php',
    type: 'post',
    data: loginData
  }).done(function (response) {
    if (response === '1') {
      let locHref = location.href;
      let homePageLink = locHref.substring(0, locHref.lastIndexOf('/')) + '/index.php';
      window.location.replace(homePageLink);
    } else {
      $(formIds.login).find('.alert').remove();
      $(formIds.login).prepend(response);
    }
  });
};

const clickSignOut = function () {
  $.ajax({
    url: 'app/process_logout.php',
    type: 'get'
  }).done(function (response) {
    if (response === '1') {
      let locHref = location.href;
      let homePageLink = locHref.substring(0, locHref.lastIndexOf('/')) + '/index.php';
      window.location.replace(homePageLink);
    } else {
      alert('Błąd podczas wylogowania');
    }
  });
};

const reservationSubmit = function () {
  let reservation = formData.reservation();
  $.ajax({
    url: 'app/process_reservation.php',
    type: 'post',
    data: reservation
  }).done(function (response) {
    $(formIds.reservation).find('.alert').remove();
    try {
      let out = JSON.parse(response);
      if (out.success === 'true') {
        $(formIds.reservation).prepend(out.response);
        $(formIds.reservation).find('input[type=submit]').prop('disabled', true);
      }
    } catch (string) {
      $(formIds.reservation).prepend(response);
    }
  });
};

const updateProfileSubmit = function () {
  let updateData = formData.updateProfile();
  console.log(updateData);
  $.ajax({
    url: 'app/process_update_profile.php',
    type: 'post',
    data: updateData
  }).done(function (response) {
    $(formIds.updateProfile).find('.alert').remove();
    reloadAnimation($(formIds.updateProfile));
    $(formIds.updateProfile).prepend(response);
    $(formIds.updateProfile).find('input').prop('disabled', true);
  });
  let reloadAnimation = (animContainer) => {
    animContainer.prepend(
      `<div class="form-group">
            <div id="path"><div id="brick"></div></div><span>Ponowne załadowanie strony, poczekaj kilka sekund.</span>
        </div>`);
    
    animate({
      duration: 4000,
      timing: function (timeFraction) {
        return Math.pow(timeFraction, 2);
      },
      draw: function (progress) {
       
        brick.style.left = progress * 91.5 + '%';
        location.reload();
      }
    });
  };
};


$(document).ready(function () {
  $(formIds.register).submit(function (event) {
    registrationSubmit();
    event.preventDefault();
    return false;
  });

  $(formIds.login).submit(function (event) {
    loginSubmit();
    event.preventDefault();
    return false;
  });

  $(formIds.logout).on('click', function (event) {
    clickSignOut();
    event.preventDefault();
    return false;
  });

  $(formIds.reservation).submit(function (event) {
    reservationSubmit();
    event.preventDefault();
    return false;
  });

  $(formIds.updateProfile).submit(function (event) {
    updateProfileSubmit();
    event.preventDefault();
    return false;
  });
});

