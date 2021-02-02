const JOGA_PER_HOUR = 20;
const PILATES_PER_HOUR = 15;
const ZUMBA_PER_HOUR = 20;
const TRENING_OBWODOWY_PER_HOUR = 20;
const BODY_PUMP_PER_HOUR = 20;
const FITBALL_PER_HOUR = 15;
const AEROBIK_PER_HOUR = 10;
const AQUA_AEROBIK_PER_HOUR = 15;
const ZDROWY_KREGOSLUP_PER_HOUR = 15;
const FITBOXING_PER_HOUR = 20;
const KALISTENIKA_PER_HOUR = 15;
const BRZUCH_STRETCH_PER_HOUR = 15;

const multiStepRsvnFormId = '#multiStepRsvnForm';
const multiStepRsvnformData = {
  
  cDate: function (dt) {
    let subject = new Date(dt);
    return [subject.getFullYear(), subject.getMonth() + 1, subject.getDate()].join('-');
  },
  d: function () {
    console.log($('input[name="cid"][isForTest="false"]'));
    return {
      cid: $('input[name="cid"][isForTest="false"]').val(),
      start: $('input[name="startDate"][isForTest="false"]').val(),
      end: $('input[name="endDate"][isForTest="false"]').val(),
      type: $('select[name="treningType"][isForTest="false"]').val(),
      requirement: $('select[name="treningRequirement"][isForTest="false"]').val(),
      cadre: $('select[name="cadre"][isForTest="false"]').val(),
      service: $('select[name="service"][isForTest="false"]').val(),
      memo: $('textarea[name="specialMemo"][isForTest="false"]').val(),
      bookedDate: multiStepRsvnformData.cDate(document.getElementsByClassName('bookedDateTxt')[0].innerHTML),
      numHours: document.getElementsByClassName('numHoursTxt')[0].innerHTML,
      totalPrice: document.getElementsByClassName('totalTxt')[0].innerHTML,
      readySubmit: $('#rsvnNextBtn').attr('readySubmit'),
    };
  },
};

// rsvn multi steps
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
  let x = document.getElementsByClassName('rsvnTab');
  x[n].style.display = 'block';
  if (n === 0) {
    document.getElementById('rsvnPrevBtn').style.display = 'none';
  } else {
    document.getElementById('rsvnPrevBtn').style.display = 'inline';
  }
  let rsvnNextBtn = $('#rsvnNextBtn');
  if (n === x.length - 1) {
    rsvnNextBtn.text('Wyślij');
    rsvnNextBtn.attr('readySubmit', 'true');
    rsvnNextBtn.attr('type', 'submit');
    rsvnNextBtn.attr('onclick', 'submitMultiStepRsvn()');
  } else {
    rsvnNextBtn.text('Zapisz');
    rsvnNextBtn.attr('readySubmit', 'false');
    rsvnNextBtn.attr('type', 'button');
    rsvnNextBtn.attr('onclick', 'rsvnNextPrev(1)');
  }
  fixStepIndicator(n);
}


function submitMultiStepRsvn() {
  let canSubmit = document.getElementById('rsvnNextBtn').getAttribute('readySubmit');
  if (!validateRsvnForm() && !canSubmit) {
    return false;
  } else {
    let d = multiStepRsvnformData.d();
    console.log(d);

    $.ajax({
      url: 'app/process_reservation.php',
      type: 'post',
      data: d,
    }).done(function (response) {
      try {
        let out = JSON.parse(response);
        if (out.success === 'true') {
          $(multiStepRsvnFormId).prepend(out.response);
          document.getElementById('rsvnNextBtn').disabled = true;
        }
      } catch (string) {
        $(multiStepRsvnFormId).prepend(response);
      }
    });
  }
}

function fixStepIndicator(n) {
  let i;
  let x = document.getElementsByClassName('step');
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(' active', '');
  }
  x[n].className += ' active';
}

function rsvnNextPrev(n) {
  let x = document.getElementsByClassName('rsvnTab');
  if (n === 1 && !validateRsvnForm()) return false;
  x[currentTab].style.display = 'none';
  currentTab = currentTab + n;
  showTab(currentTab);
}

function validateRsvnForm() {
  let tab = document.getElementsByClassName('rsvnTab');
  let valid = true;
  let inputs = tab[currentTab].getElementsByTagName('input');
  for (let i = 0; i < inputs.length; i++) {
    if (inputs[i].hasAttribute('required')) {
      if (inputs[i].value === '') {
        inputs[i].className += ' invalid';
        valid = false;
      }
    }
  }

  let selects = tab[currentTab].getElementsByTagName('select');
  for (let i = 0; i < selects.length; i++) {
    if (selects[i].hasAttribute('required')) {
      if (selects[i].value === '') {
        selects[i].className += ' invalid';
        valid = false;
      }
    }
  }

  if (valid) {
    document.getElementsByClassName('step')[currentTab].className += ' finish';
    new ReservationCost(
      $('select[name="treningType"][isForTest="false"]').val(),
      $('input[name="startDate"][isForTest="false"]').val(),
      $('input[name="endDate"][isForTest="false"]').val()
    ).displayAll();
  }
  return valid;
}

class ReservationCost {
  constructor(treningType, startDate, endDate) {
    let today = new Date();
    this.bookDate = today.toDateString();
    this.treningType = treningType;
    this.startDate = new Date(startDate);
    this.endDate = new Date(endDate);
  }

  priceByTreningType() {
    if (this.treningType === 'Joga') {
      return JOGA_PER_HOUR;
    }
    if (this.treningType === 'Pilates') {
      return PILATES_PER_HOUR;
    }
    if (this.treningType === 'Zumba') {
      return ZUMBA_PER_HOUR;
    }
    if (this.treningType === 'Trening obwodowy') {
      return TRENING_OBWODOWY_PER_HOUR;
    }
    if (this.treningType === 'Body pump') {
      return BODY_PUMP_PER_HOUR;
    }
    if (this.treningType === 'FitBall') {
      return FITBALL_PER_HOUR;
    }
    if (this.treningType === 'Aerobik') {
      return AEROBIK_PER_HOUR;
    }
    if (this.treningType === 'Aqua Aerobik') {
      return AQUA_AEROBIK_PER_HOUR;
    }
    if (this.treningType === 'Zdrowy kręgosłup') {
      return ZDROWY_KREGOSLUP_PER_HOUR;
    }
    if (this.treningType === 'FitBoxing') {
      return FITBOXING_PER_HOUR;
    }
    if (this.treningType === 'Kalistenika') {
      return KALISTENIKA_PER_HOUR;
    }
    if (this.treningType === 'Brzuch + stretch') {
      return BRZUCH_STRETCH_PER_HOUR;
    }
  }

  numHours() {
    console.log(this.startDate, this.endDate);
    return new UtilityFunctions().dateDiffInDays(this.startDate, this.endDate);
  }

  displayBookedDate() {
    document.getElementsByClassName('bookedDateTxt')[0].innerHTML = this.bookDate;
  }

  displayTreningPrice() {
    document.getElementsByClassName('treningPriceTxt')[0].innerHTML = this.numHours() * this.priceByTreningType() + 'zł';
  }

  displayNumHours() {
    document.getElementsByClassName('numHoursTxt')[0].innerHTML = this.numHours().toString();
    document.getElementsByClassName('treningPricePerHourTxt')[0].innerHTML = this.priceByTreningType();
  }

  displayFromTo() {
    let start = this.startDate.getFullYear() + '-' + (this.startDate.getMonth() + 1) + '-' + (this.startDate.getDate());
    let end = this.endDate.getFullYear() + '-' + (this.endDate.getMonth() + 1) + '-' + (this.endDate.getDate());
    document.getElementsByClassName('fromToTxt')[0].innerHTML = start + ' do ' + end;
  }

  displayTotalCost() {
    let totalTreningPrice = this.numHours() * this.priceByTreningType();
    let totalTxt = document.getElementsByClassName('totalTxt')[0].innerHTML;
    document.getElementsByClassName('totalTxt')[0].innerHTML = totalTreningPrice;
  }

  displayAll() {
    this.displayBookedDate();
    this.displayTreningPrice();
    this.displayNumHours();
    this.displayFromTo();
    this.displayTotalCost();
  }
}
