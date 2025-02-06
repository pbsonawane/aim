'use strict';
const isSite = (window.location.href.indexOf('draggable.github.io') !== -1);
let container = document.querySelector('#build-form');
let renderContainer = document.querySelector('.render-form');
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
  svgSprite: 'https://draggable.github.io/formeo/assets/img/formeo-sprite.svg',
  // debug: true,
  sessionStorage: false,
  editPanelOrder: ['attrs', 'options']
};


var  formeo = new window.Formeo(formeoOpts);
console.log(formeo);
let editing = true;

// let debugWrap = document.getElementById('debug-wrap');
// let debugBtn = document.getElementById('debug-demo');
let localeSelect = document.getElementById('locale');
let toggleEdit = document.getElementById('renderForm');
let viewDataBtn = document.getElementById('viewData');
let resetDemo = document.getElementById('reloadBtn');
let setDataBtn = document.getElementById('setDataBtn');


// debugBtn.onclick = function() {
//   debugWrap.classList.toggle('open');
// };

resetDemo.onclick = function() {
  window.sessionStorage.removeItem('formData');
  location.reload();
};
setDataBtn.onclick = evt => {
//setDataBtn.onclick = function() {
  alert("set");
  var  formeoOpts = {
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
    svgSprite: 'https://draggable.github.io/formeo/assets/img/formeo-sprite.svg',
    // debug: true,
    sessionStorage: false,
    editPanelOrder: ['attrs', 'options']
  };
  
 /* var jsonDataAsString = {
    "id": "a36f36b1-e767-43a6-925a-d57dc3b0ae88",
    "settings": {},
    "stages": {
      "80b2b5d1-aa38-406b-946a-79615a5cf012": {
        "id": "80b2b5d1-aa38-406b-946a-79615a5cf012",
        "settings": {},
        "rows": [
          "a7557a82-c993-49c9-a0d0-7d6d36d55aa5",
          "4f99f032-9c30-481f-8c01-5f5053a832e6"
        ]
      }
    },
    "rows": {
      "a7557a82-c993-49c9-a0d0-7d6d36d55aa5": {
        "columns": [
          "c8f3cb86-407b-4bf9-91df-63bcbc62049e"
        ],
        "id": "a7557a82-c993-49c9-a0d0-7d6d36d55aa5",
        "config": {
          "fieldset": false,
          "legend": "",
          "inputGroup": false
        },
        "attrs": {
          "className": "f-row"
        }
      },
      "4f99f032-9c30-481f-8c01-5f5053a832e6": {
        "columns": [
          "e534bb3e-78f6-4406-a4cf-924ee55313e0"
        ],
        "id": "4f99f032-9c30-481f-8c01-5f5053a832e6",
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
      "c8f3cb86-407b-4bf9-91df-63bcbc62049e": {
        "fields": [
          "2cbac795-c8ef-4397-8b1f-9e7bd480c896"
        ],
        "id": "c8f3cb86-407b-4bf9-91df-63bcbc62049e",
        "config": {
          "width": "100%"
        },
        "className": []
      },
      "e534bb3e-78f6-4406-a4cf-924ee55313e0": {
        "fields": [
          "048a5675-6526-4cc4-a78f-13d7766eac2d"
        ],
        "id": "e534bb3e-78f6-4406-a4cf-924ee55313e0",
        "config": {
          "width": "100%"
        },
        "className": []
      }
    },
    "fields": {
      "2cbac795-c8ef-4397-8b1f-9e7bd480c896": {
        "tag": "input",
        "attrs": {
          "type": "number",
          "required": false,
          "className": ""
        },
        "config": {
          "label": "Number",
          "disabledAttrs": [
            "type"
          ]
        },
        "meta": {
          "group": "common",
          "icon": "hash",
          "id": "number"
        },
        "fMap": "attrs.value",
        "id": "2cbac795-c8ef-4397-8b1f-9e7bd480c896"
      },
      "048a5675-6526-4cc4-a78f-13d7766eac2d": {
        "tag": "select",
        "config": {
          "label": "Select"
        },
        "attrs": {
          "required": false,
          "className": ""
        },
        "meta": {
          "group": "common",
          "icon": "select",
          "id": "select"
        },
        "options": [
          {
            "label": "Option 1",
            "value": "option-1",
            "selected": false
          },
          {
            "label": "Option 2",
            "value": "option-2",
            "selected": false
          },
          {
            "label": "Option 3",
            "value": "option-3",
            "selected": false
          },
          {
            "label": "Option 4",
            "value": "option-4",
            "selected": false
          }
        ],
        "id": "048a5675-6526-4cc4-a78f-13d7766eac2d"
      }
    }
  }*/
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
    evt.target.innerHTML = 'Edit Form';
  } else {
    evt.target.innerHTML = 'Render Form';
  }

  return editing = !editing;
};

viewDataBtn.onclick = evt => {

  alert(formeo.formData);
  console.log(formeo.formData);
};


let formeoLocale = window.sessionStorage.getItem('formeo-locale');
if (formeoLocale) {
  localeSelect.value = formeoLocale;
}

localeSelect.addEventListener('change', function() {
  window.sessionStorage.setItem('formeo-locale', localeSelect.value);
  formeo.i18n.setLang(localeSelect.value);
});

document.getElementById('control-filter')
.addEventListener('input', function(e) {
  formeo.controls.actions.filter(e.target.value);
});

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
  (function(i, s, o, g, r, a, m) {
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
  ga('send', 'pageview');
}
