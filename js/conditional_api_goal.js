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
var ConditionalApiGoalObj = function (args) {
    window.conditionalApiGoal = [];

    this.init = function () {
        this.strings = isSet(args.strings) ? args.strings : {};
        this.goalName = args.goalName;
        this.logicObject = {
            conditionalLogic: args.logicObject
        };
        var fcobj = this;

        /**
         * Act on document ready
         * */
        setTimeout(function () {
            conditionalApiGoal = fcobj;
            var form = jQuery('input#conditional_api_goal_conditional_logic').parents('form');

            gform.addFilter('gform_conditional_object', 'ConditionalApiGoalObject');
            gform.addFilter('gform_conditional_logic_name', 'ConditionalApiGoalName');
            gform.addFilter('gform_conditional_logic_description', 'ConditionalApiGoalDescription');

            jQuery("#conditional_api_goal_conditional_logic").is(":checked") ? jQuery("#conditional_api_goal_name_container").show() : jQuery("#conditional_api_goal_name_container").hide();
            ToggleConditionalLogic(true, "conditional_api_goal");

            /**
             * Handle submit event on the form
             * */
            form.on('submit', function (event) {
                x = fcobj.logicObject.conditionalLogic;
                x = JSON.stringify({ conditionalLogic: x });

                jQuery('input#conditional_api_goal_logic_object').val(x);
            });
        }, 0);
    };

    this.init();
};

function ConditionalApiGoalName(object, objectType) {
    if (objectType != 'conditional_api_goal') return objectType;

    return 'conditional_api_goal';
}

function ConditionalApiGoalObject(object, objectType) {
    if (objectType != 'conditional_api_goal') return object;

    return conditionalApiGoal['logicObject'];
}

function ConditionalApiGoalDescription(description, descPieces, objectType, obj) {
    if (objectType != 'conditional_api_goal')
        return description;

    descPieces.actionType = descPieces.actionType.replace('<select', '<select style="display:none;"');
    descPieces.objectDescription = conditionalApiGoal.strings.objectDescription;
    var descPiecesArr = makeArray(descPieces);

    return descPiecesArr.join(' ');
}
