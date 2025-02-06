<!--<form name ="mon_alerts_frm" id="mon_alerts_frm" action="" method="POST"  class="form-horizontal">
	<div class="alert hidden alert-dismissable" id="msg_div_pop"></div>
		<input type="hidden" name="action" value="">
<div class="topbar-right">
	<ul class="nav navbar-nav navbar-right">
		<li class="dropdown dropdown-item-slide  p5 mr5">
			<span title="Acknowledge" data-value="ack" class="alrtaction fa fa-thumbs-up fa-2x"></span>
		</li>
		<li class="dropdown dropdown-item-slide  p5 mr5">
			<span title="Unacknowledge" data-value="unack" class="alrtaction fa fa-thumbs-o-up fa-2x"></span>
		</li>
		<li class="dropdown dropdown-item-slide-note p5 mr5">
			<span title="Note" data-value="note" class=" alrtaction fa fa-pencil-square-o fa-2x"></span>
			<ul class="dropdown-menu dropdown-hover pn w450 bg-white animated animated-shorter fadeIn" role="menu">
				<li class="bg-light p8">
					<span class="fw600 pl5 lh30">Add Settings Template</span>
				</li>
				<li class="p8">
					<div class="form-group">
						<label for="Description" class="col-md-3 control-label">Add Note</label>
						<div class="col-md-8">
							<textarea id="description" name="description" class="form-control input-sm"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"></label>
						<div class="col-xs-3">
							<button id="submit_note" type="button" class="btn btn-success btn-block">Submit</button>
						</div>
				   </div>
				</li>  
			</ul>
		</li>
		
	</ul>

</div>
<style>
	.fa-2x{
		font-size:1.5em
	}
</style>-->
<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">
  <div class="site-wrap">
    <section id="main_content" class="inner">
      <form id="build-form" class="build-form clearfix"></form>
      <div class="render-form"></div>
    </section>
    <div class="render-btn-wrap">
      <button id="renderForm" class="btn btn-info">Preview Form</button>
      <button id="viewData" class="btn btn-success">console.log Data</button>
      <button id="reloadBtn" class="btn btn-danger">Reset Editor</button>
      <!--<button id="setDataBtn" class="btn btn-default">Set Data</button>-->

      <!--<button id="setDataBtn" type="button" class="btn btn-success btn-block">Set Data</button>-->

<button id="submit_note" type="button" class="btn btn-success">Submit</button>
    </div>
  </div>


<!--</form>-->
<!--<script language="javascript" type="text/javascript" src="enlight/scripts/formBuilder/form-builder.min.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/formBuilder/form-render.min.js"></script> -->

<script language="javascript" type="text/javascript" src="enlight/scripts/formeo-master/formeo.min.js"></script>
 
<script language="javascript" type="text/javascript" src="enlight/scripts/formeo-master/js/demo.js"></script>
<script>
$(document).ready(function() 
{
//	setDataedit();	
});

 function setDataedit(){
    let container = document.querySelector('#build-form');
  alert("set...");
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
   // svgSprite: 'https://draggable.github.io/formeo/assets/img/formeo-sprite.svg',
    svgSprite: "enlight/scripts/formeo-master/img/formeo-sprite.svg",
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
</script>