// jQuery time
let current_fs;
let next_fs;
let previous_fs; // fieldsets
let left;
let opacity;
let slide; // fieldset properties which we will animate
let animating; // flag to prevent quick multi-click glitches
const maxUploadSize = 1024; // 1024kb == 1mb
const educationSpanCount = {};
const form = $('#msform');

$.validator.addMethod('refRequired', $.validator.methods.required, 'field is required');
$.validator.addMethod(
  'refMin',
  $.validator.methods.minlength,
  $.validator.format('filed must have at least {0} caharacters'),
);
$.validator.addMethod('filesize', (value, element, arg) => {
  const minsize = 1000; // min 1kb
  if (value > minsize && value <= arg) {
    return true;
  }
  return false;
});
$.validator.addClassRules('ref_input', { refRequired: true, refMin: 2 });

$('.next').click(function () {
  const minlength3 = {
    required: true,
    minlength: 2,
  };
  const isNumber = {
    required: true,
    digits: true,
    minlength: 10,
  };
  const isEmail = {
    required: true,
    email: true,
  };
  form.validate({
    rules: {
      title: {
        required: true,
      },
      firstname: minlength3,
      middlename: {
        required: false,
        minlength: 2,
      },
      surname: minlength3,
      mobilePhone: {
        required: true,
        minlength: 10,
        digits: true,
      },
      workPhone: {
        required: false,
        digits: true,
        minlength: 7,
      },
      email: true,
      'requirement_title[]': {
        required: true,
        minlength: 3,
      },
      'requirement_file[]': {
        required: true,
        maxsize: 1048576, // 1mb
        accept:
          'application/pdf,image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
      },
      'company[]': minlength3,
      'year[]': minlength3,
      'alumni_course[]': {
        required: true,
      },
      'alumni_title[]': {
        required: true,
      },
      emergency_number: isNumber,
      ref_number1: isNumber,
      ref_number2: isNumber,
      ref_email1: isEmail,
      ref_email2: isEmail,
      ref_input: true,
      'qualiication[]': {
        required: true,
        accept: 'application/pdf,image/*',
      },
    },
    messages: {
      title: {
        required: 'Your title is required',
      },
      'requirement_file[]': {
        maxsize: ' file size must not be more than 1mb.',
        accept: 'Please upload .jpg or .png or .pdf or .doc.',
        required: 'Please upload a valid file.',
      },
    },
  });
  if (form.valid()) {
    moveToNext(this);
  } else {
  }
});

function moveToNext(elem) {
  const $this = elem;
  if (animating) return false;
  animating = true;

  current_fs = $($this).parent();
  next_fs = $($this)
    .parent()
    .next();

  // activate next step on progressbar using the index of next_fs
  $('#progressbar li')
    .eq($('fieldset').index(next_fs))
    .addClass('active');

  // show the next fieldset
  next_fs.show();
  // hide the current fieldset with style
  current_fs.animate(
    { opacity: 0 },
    {
      step(now, mx) {
        // as the opacity of current_fs reduces to 0 - stored in "now"
        // 1. scale current_fs down to 80%
        scale = 1 - (1 - now) * 0.2;
        // 2. bring next_fs from the right(50%)
        left = `${now * 30}%`;
        // 3. increase opacity of next_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({
          transform: `scale(${slide})`,
          position: 'absolute',
        });
        next_fs.css({ left, opacity });
      },
      duration: 2000,
      complete() {
        current_fs.hide();
        animating = false;
      },
      // this comes from the custom easing plugin
      easing: 'easeInOutBack',
    },
  );
}

$('.previous').click(function () {
  if (animating) return false;
  animating = true;

  current_fs = $(this).parent();
  previous_fs = $(this)
    .parent()
    .prev();

  // de-activate current step on progressbar
  $('#progressbar li')
    .eq($('fieldset').index(current_fs))
    .removeClass('active');

  // show the previous fieldset
  previous_fs.show();
  // hide the current fieldset with style
  current_fs.animate(
    { opacity: 0 },
    {
      step(now, mx) {
        // as the opacity of current_fs reduces to 0 - stored in "now"
        // 1. scale previous_fs from 80% to 100%
        scale = 0.8 + (1 - now) * 0.2;
        // 2. take current_fs to the right(50%) - from 0%
        left = `${(1 - now) * 50}%`;
        // 3. increase opacity of previous_fs to 1 as it moves in
        opacity = 1 - now;
        current_fs.css({ left });
        previous_fs.css({
          transform: `scale(${scale})`,
          opacity,
        });
      },
      duration: 2000,
      complete() {
        current_fs.hide();
        animating = false;
      },
      // this comes from the custom easing plugin
      easing: 'easeInOutBack',
    },
  );
});

$('.submit').click((e) => {
  e.preventDefault();

  const validator = form.validate();
  validator.element('#acceptTerms');
  if (form.valid()) {
    $('.terms-invalid').hide();
  } else {
    $('.terms-invalid').css('display', 'block');
  }

  $('form#msform').submit();
});

$('.passport').on('click', (e) => {
  $('.passport-upload').click();
});
$('.passport-upload').on('change', (e) => {
  if (window.File && window.FileReader && window.FileList && window.Blob) {
    const file = e.target.files[0];
    reachMaxSize = file.size / (maxUploadSize * maxUploadSize) > 1;
    if (!file.type.match('image.*') || reachMaxSize) {
      alert('invalid file selected, must be an image not more than 1mb of size');
      $('.passport-upload')
        .wrap('<form>')
        .closest('form')
        .get(0)
        .reset();
      $('.passport-upload').unwrap();
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      $('.passport>span').hide();
      $('#uploadedImg')
        .attr('src', e.target.result)
        .show();
    };
    reader.readAsDataURL(file);
  }
});

$('.addMore').on('click', (e) => {
  const newSpan = document.createElement('div');
  newSpan.classList.add('education');
  if (e.target.dataset.type === 'file') {
    newSpan.innerHTML = `
                        <div class="row mb-2"><div class="col-md-6">
                        <input type="text" placeholder="BSC Certificate" name="school[]" required>
                    </div>
                    <div class="col-md-6">
                        <input type="file" placeholder="Qualification" name="qualiication[]" required>
                    </div></div>
                     `;
  } else if (e.target.dataset.type === 'text') {
    newSpan.innerHTML = `
                        <div class="row mb-2"><div class="col-md-6">
                        <input type="text" placeholder="Company name/position" name="comapany[]" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" placeholder="Duration e.g. 2017-2019" name="year[]" required>
                    </div></div>
                     `;
  }

  const targetClass = e.target.dataset.target;
  $(` .${targetClass}`).append(newSpan);

  if ($(`#subtract${targetClass}`).hasClass('hidden')) {
    educationSpanCount[targetClass] = 1;
    $(`#subtract${targetClass}`).removeClass('hidden');
  } else {
    educationSpanCount[targetClass] += 1;
  }
  document.querySelector('.one').style.visibilty = 'hidden';
});

$('.subtractMore').on('click', (e) => {
  const targetClass = e.target.dataset.target;
  if (educationSpanCount[targetClass] > 0) {
    $(` .${targetClass} > .education`)
      .last()
      .remove();
    educationSpanCount[targetClass] -= 1;
  }

  if (educationSpanCount[targetClass] < 1) {
    $(`#subtract${targetClass}`).addClass('hidden');
  }
});

$('.alumniCheck').on('change', (elem) => {
  const val = $('input:checked.alumniCheck').attr('value');
  const alumniInput = $('#alumniInput');
  if (val == 'yes' && alumniInput.hasClass('hidden')) {
    alumniInput.removeClass('hidden');
  } else {
    alumniInput.addClass('hidden');
  }
});
