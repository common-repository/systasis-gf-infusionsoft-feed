/**
 * See js/form_admin.js#1442
 */
/**
 * Systasis Gravity Forms Infusionsoft Feed Add-On
 *
 * @since     1.0
 * @package   GravityForms
 * @author    Systasis Computer Systems, Inc.
 * @version	  1.0
 * @copyright Copyright (c) 2017, Systasis Computer Systems
 */
var CombineFieldsObj = function (args) {
	window.combineFields = [];

	this.init = function () {
		this.strings = isSet(args.strings) ? args.strings : {};
		this.cssClass = args.cssClass;
		var fcobj = this;

		/**
		 * Act on document ready
		 * */
		setTimeout(function () {
			// Stolen from js/conditional_api_goal.js
			var form = jQuery('input#conditional_api_goal_conditional_logic').parents('form');

			gform.addFilter('gform_combine_fields_name', 'CombineFieldsName');
			gform.addFilter('gform_combine_fields_description', 'CombineFieldsDescription');

			jQuery("#contactcombinefields").is(":checked") ? jQuery("#combine_fields_container").show() : jQuery("#combine_fields_container").hide();
		}, 0);
	};

	this.init();
};

function CombineFieldsName(object, objectType) {
	if (objectType != 'combine_fields') return objectType;

	return 'combine_fields';
}

function CombineFieldsDescription(description, descPieces, objectType, obj) {
	if (objectType != 'combine_fields')
		return description;

	descPieces.actionType = descPieces.actionType.replace('<select', '<select style="display:none;"');
	descPieces.objectDescription = conditionalApiGoal.strings.objectDescription;
	var descPiecesArr = makeArray(descPieces);

	return descPiecesArr.join(' ');
}
