'use strict';
const isSite = (window.location.href.indexOf('draggable.github.io') !== -1);
let container = document.querySelector('#build-form');
let renderContainer = document.querySelector('.render-form');
//console.log("container: "+container);
//console.log("container: "+renderContainer);
let formeoOpts = {
  container: container,
  i18n: {
    preloaded: {
      'en-US': {'row.makeInputGroup': ' Repeatable Region'}
    }
  },
  allowEdit: true,
  controls: {
    sortable: false,
    groupOrder: [
      'common',
      'html',
    ],
    elements: [
//     {
//   tag: 'input',
//   attrs: {
//     type: 'radio',
//     required: false
//   },
//   config: {
//     label: 'Radio Group',
//     disabledAttrs: ['type']
//   },
//   meta: {
//     group: 'common',
//     icon: 'radio-group',
//     id: 'radio'
//   },
//   options: (() => {
//     let options = [1, 2, 3].map(i => {
//       return {
//         label: 'Radio ' + i,
//         value: 'radio-' + i,
//         selected: false
//       };
//     });
//     let otherOption = {
//         label: 'Other',
//         value: 'other',
//         selected: false
//       };
//     options.push(otherOption);
//     return options;
//   })(),
//   action: {
//     mouseover: evt => {
//       console.log(evt);
//       const {target} = evt;
//       if (target.value === 'other') {
//         const otherInput = target.cloneNode(true);
//         otherInput.type = 'text';
//         target.parentElement.appendChild(otherInput);
//       }
//     }
//   }
// },

    ],
    elementOrder: {
      common: [
      'button',
      'checkbox',
      'date-input',
      'hidden',
      'upload',
      'number',
      'radio',
      'select',
      'text-input',
      'textarea',
      ]
    }
  },
  events: {
    // onUpdate: console.log,
    // onSave: console.log
  },
  //svgSprite: 'https://draggable.github.io/formeo/assets/img/formeo-sprite.svg',
  svgSprite: SITE_URL+"/enlight/scripts/formeo-master/img/formeo-sprite.svg",
  // debug: true,
  sessionStorage: false,
  editPanelOrder: ['attrs', 'options']
};


var  formeo = new window.Formeo(formeoOpts);


//console.log(formeo);
let editing = true;

// let debugWrap = document.getElementById('debug-wrap');
// let debugBtn = document.getElementById('debug-demo');
//let localeSelect = document.getElementById('locale');
/*
let toggleEdit = document.getElementById('renderForm');
let viewDataBtn = document.getElementById('viewData');
let resetDemo = document.getElementById('reloadBtn');
let setDataBtn = document.getElementById('setDataBtn');*/


let toggleEdit = document.querySelector('#renderForm');
let viewDataBtn = document.querySelector('#viewData');
//let resetDemo = document.querySelector('#reloadBtn');
//let setDataBtn = document.querySelector('#setDataBtn');


// debugBtn.onclick = function() {
//   debugWrap.classList.toggle('open');
// };

/* 10 Oct 2020 //Commented as of No Use for ur projects
resetDemo.onclick = function() {
  window.sessionStorage.removeItem('formData');
  location.reload();
};*/
//setDataBtn.onclick = evt => {
//setDataBtn.onclick = function() {
function setDataedit(){
  //alert("set");
  var  formeoOpts = {
    container: container,
    i18n: {
      preloaded: {
        //'en-US': {'row.makeInputGroup': ' Repeatable Region'}
        'fr-FR': { "fr-FR": "" }
      }
    },
    allowEdit: true,
    controls: {
      sortable: false,
      groupOrder: [
        'common',
        'html',
      ],
      elements: [
  //     {
  //   tag: 'input',
  //   attrs: {
  //     type: 'radio',
  //     required: false
  //   },
  //   config: {
  //     label: 'Radio Group',
  //     disabledAttrs: ['type']
  //   },
  //   meta: {
  //     group: 'common',
  //     icon: 'radio-group',
  //     id: 'radio'
  //   },
  //   options: (() => {
  //     let options = [1, 2, 3].map(i => {
  //       return {
  //         label: 'Radio ' + i,
  //         value: 'radio-' + i,
  //         selected: false
  //       };
  //     });
  //     let otherOption = {
  //         label: 'Other',
  //         value: 'other',
  //         selected: false
  //       };
  //     options.push(otherOption);
  //     return options;
  //   })(),
  //   action: {
  //     mouseover: evt => {
  //       console.log(evt);
  //       const {target} = evt;
  //       if (target.value === 'other') {
  //         const otherInput = target.cloneNode(true);
  //         otherInput.type = 'text';
  //         target.parentElement.appendChild(otherInput);
  //       }
  //     }
  //   }
  // },
  
      ],
      elementOrder: {
        common: [
        'button',
        'checkbox',
        'date-input',
        'hidden',
        'upload',
        'number',
        'radio',
        'select',
        'text-input',
        'textarea',
        ]
      }
    },
    events: {
      // onUpdate: console.log,
      // onSave: console.log
    },
   // svgSprite: 'https://draggable.github.io/formeo/assets/img/formeo-sprite.svg',
    svgSprite: SITE_URL+"/enlight/scripts/formeo-master/img/formeo-sprite.svg",
    // debug: true,
    sessionStorage: false,
    editPanelOrder: ['attrs', 'options']
  };
  
   var jsonDataAsString ={
    "id": "159678f4-5a4f-4d9f-a308-c24b208ee867",
    "settings": {},
    "stages": {
      "965e62a4-2df4-4427-906a-43898d0396e4": {
        "id": "965e62a4-2df4-4427-906a-43898d0396e4",
        "settings": {},
        "rows": [
          "df97764d-95c7-4519-90ca-4a0cd0bf9429"
        ]
      }
    },
    "rows": {
      "df97764d-95c7-4519-90ca-4a0cd0bf9429": {
        "columns": [
          "6e136ec7-63bd-458a-95db-8e2efdff271e",
          "bfd23cc4-2ed2-4e80-a305-0638df625463"
        ],
        "id": "df97764d-95c7-4519-90ca-4a0cd0bf9429",
        "config": {
          "fieldset": false,
          "legend": "",
          "inputGroup": false
        },
        "attrs": {
          "className": "f-row"
        }
      }
    },
    "columns": {
      "6e136ec7-63bd-458a-95db-8e2efdff271e": {
        "fields": [
          "5984a194-e397-440d-91ae-22638d1f4841",
          "d15f0505-457d-44b0-96fc-8b8375c2151c"
        ],
        "id": "6e136ec7-63bd-458a-95db-8e2efdff271e",
        "config": {
          "width": "50%"
        },
        "className": []
      },
      "bfd23cc4-2ed2-4e80-a305-0638df625463": {
        "fields": [
          "8848c07b-4522-4437-8d64-abe6a878422b"
        ],
        "id": "bfd23cc4-2ed2-4e80-a305-0638df625463",
        "config": {
          "width": "50%"
        },
        "className": []
      }
    },
    "fields": {
      "5984a194-e397-440d-91ae-22638d1f4841": {
        "tag": "input",
        "attrs": {
          "type": "checkbox",
          "required": false
        },
        "config": {
          "label": "Checkbox/Group",
          "disabledAttrs": [
            "type"
          ]
        },
        "meta": {
          "group": "common",
          "icon": "checkbox",
          "id": "checkbox"
        },
        "options": [
          {
            "label": "Checkbox 1",
            "value": "checkbox-1",
            "selected": true
          }
        ],
        "id": "5984a194-e397-440d-91ae-22638d1f4841"
      },
      "8848c07b-4522-4437-8d64-abe6a878422b": {
        "tag": "input",
        "attrs": {
          "type": "date",
          "required": false,
          "className": ""
        },
        "config": {
          "disabledAttrs": [
            "type"
          ],
          "label": "Date"
        },
        "meta": {
          "group": "common",
          "icon": "calendar",
          "id": "date-input"
        },
        "id": "8848c07b-4522-4437-8d64-abe6a878422b"
      },
      "d15f0505-457d-44b0-96fc-8b8375c2151c": {
        "tag": "button",
        "attrs": {
          "className": [
            {
              "label": "Grouped",
              "value": "f-btn-group"
            },
            {
              "label": "Un-Grouped",
              "value": "f-field-group"
            }
          ]
        },
        "config": {
          "label": "Button",
          "hideLabel": true,
          "disabledAttrs": [
            "type"
          ]
        },
        "meta": {
          "group": "common",
          "icon": "button",
          "id": "button"
        },
        "options": [
          {
            "label": "Button",
            "type": [
              {
                "label": "Button",
                "value": "button",
                "selected": true
              },
              {
                "label": "Reset",
                "value": "reset"
              },
              {
                "label": "submit",
                "value": "submit"
              }
            ],
            "className": [
              {
                "label": "default",
                "value": "",
                "selected": true
              },
              {
                "label": "Primary",
                "value": "primary"
              },
              {
                "label": "Danger",
                "value": "error"
              },
              {
                "label": "Success",
                "value": "success"
              },
              {
                "label": "Warning",
                "value": "warning"
              }
            ]
          }
        ],
        "id": "d15f0505-457d-44b0-96fc-8b8375c2151c"
      }
    }
  }
  //let opts = $.extend({},formeoOptions);
  formeo = new window.Formeo(formeoOpts, jsonDataAsString);
};
toggleEdit.onclick = evt => {
  document.body.classList.toggle('form-rendered', editing);
  if (editing) {
    formeo.render(renderContainer);
    evt.target.innerHTML = trans('label.btn_edit_form');
  } else {
    evt.target.innerHTML = trans('label.btn_render_form');
  }

  return editing = !editing;
};

viewDataBtn.onclick = evt => {

  //alert(formeo.formData);
  $(".details").val(formeo.formData);
  //console.log(formeo.formData);
};


/*let formeoLocale = window.sessionStorage.getItem('formeo-locale');
if (formeoLocale) {
  localeSelect.value = formeoLocale;
}

localeSelect.addEventListener('change', function() {
  window.sessionStorage.setItem('formeo-locale', localeSelect.value);
  formeo.i18n.setLang(localeSelect.value);
});*/

//document.getElementById('control-filter')
/*document.querySelector('#control-filter')
.addEventListener('input', function(e) {
  formeo.controls.actions.filter(e.target.value);
});
*/
if (isSite) {
  ((window.gitter = {}).chat = {}).options = {
    room: 'draggable/formeo'
  };

  // Gitter
  (function(d) {
    let js;
    js = d.createElement('script');
    js.src = '//sidecar.gitter.im/dist/sidecar.v1.js';
    d.body.appendChild(js);
  }(document));

  // Facepoop
  (function(d, s, id) {
    let js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = '//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=940846562669162';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Twitter
  (function(d, s, id) {
    let js, fjs = d.getElementsByTagName(s)[0],
      p = /^http:/.test(d.location) ? 'http' : 'https';
    if (!d.getElementById(id)) {
      js = d.createElement(s);
      js.id = id;
      js.src = p + '://platform.twitter.com/widgets.js';
      fjs.parentNode.insertBefore(js, fjs);
    }
  })(document, 'script', 'twitter-wjs');

  // Google analytics
 /* (function(i, s, o, g, r, a, m) {
    i.GoogleAnalyticsObject = r;
    i[r] = i[r] || function() {
      (i[r].q = i[r].q || []).push(arguments);
    }, i[r].l = 1 * new Date();
    a = s.createElement(o),
      m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m);
  })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

  ga('create', 'UA-79014176-2', 'auto');
  ga('send', 'pageview');*/
}
