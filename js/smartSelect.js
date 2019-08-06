// JavaScript source code

//EXAMPLE//var children = [];//children[0] = { "nombre": "Jorge", "id": 100, "control": 500 };//children[1] = { "nombre": "Luis", "id": 101, "control": 500 };//children[2] = { "nombre": "Hector", "id": 102, "control": 500 };//children[3] = { "nombre": "Enrique", "id": 103, "control": 500 };//children[4] = { "nombre": "Jason", "id": 104, "control": 500 };//var atributos = [];//atributos[0] = "id";//atributos[1] = "control";//var atributoNombre = "nombre";//var callBackAtributos = [];//callBackAtributos[0] = "id";//callBackAtributos[1] = "control";//var smartSelect1 = new smartSelect("#smartSelect1");//$(document).ready(function () {
//    smartSelect1.loadChildren(children, atributos, atributoNombre, callBackAtributos);
//    smartSelect1.callBackFunction = function (data) {
//        console.log(data);
//    };
//    smartSelect1.onChangeCallBackFunction = function (inputValue) {
//        console.log(inputValue);
//    };
//});

function smartSelect(idOfMainContainer) {
    this.idMainContainer = idOfMainContainer;
    this.smartSelect = null;
    this.childrenDom = null;
    this.children = null;
    this.attributes = null;
    this.nameAttribute = null;
    this.inputDom = null;
    this.callBackAttributes = null;
    this.selectionFromListCallBackFunction = null;
    this.inputOnChangeCallBackFunction = null;
    var me = this;

    this.loadChildren = function (children, arrOfAttributes, nameAttribute,callBackAttributes) {
        this.children = children;
        this.attributes = arrOfAttributes;
        this.nameAttribute = nameAttribute;
        this.callBackAttributes = callBackAttributes;

        var optionContainer = $(this.smartSelect.children('div')[0]);
        optionContainer.html("");

        var childrenQty = this.children.length;
        var attributesQty = this.attributes.length;

        var i = 0;
        for (i; i < childrenQty; i++) {
            var child = this.children[i];
            var j = 0;
            var attributes = '';
            for (j; j < attributesQty; j++)
                attributes = attributes + ' data-' + this.attributes[j] + '="' + child[this.attributes[j]] + '"';

            optionContainer.append('<option ' + attributes + ' > ' + child[this.nameAttribute] + ' </option>');
        }

        this.childrenDom = $(this.smartSelect.children('div')[0]).children();
    }


    this.whoIsActive = function () {
        var childrenQty = this.childrenDom.length;
        var i = 0;
        for (i; i < childrenQty; i++) {
            var child = $(this.childrenDom[i]);
            if (child.hasClass('smartSelect_active'))
                return [child, i];
        }
        return [null, -1];
    }

    this.removeClassFromAllChildren = function (activeChildIndex) {
        var childrenQty = this.childrenDom.length;
        var i = 0;
        for (i; i < childrenQty; i++) {
            if (i == activeChildIndex)
                continue;

            $(this.childrenDom[i]).removeClass('smartSelect_active');
        }
    }

    this.selectUpperChildren = function (activeChild) {
        var lastChildIndex = this.childrenDom.length - 1;
        if (activeChild[1] === 0 || lastChildIndex === 0) {
            //Do nothing...
        }
        else if (activeChild[1] === -1) {
            $(this.childrenDom[0]).addClass('smartSelect_active');
        }
        else {
            me.removeClassFromAllChildren(activeChild[1] - 1);
            $(this.childrenDom[activeChild[1] - 1]).addClass('smartSelect_active');
        }
    }

    this.selectLowerChildren = function(activeChild) {
        var lastChildIndex = this.childrenDom.length - 1;
        if (activeChild[1] === lastChildIndex || lastChildIndex === 0) {
            //Do nothing
        }
        else if (activeChild[1] === -1) {
            $(this.childrenDom[0]).addClass('smartSelect_active');
        }
        else {
            me.removeClassFromAllChildren(activeChild[1] + 1);
            $(this.childrenDom[activeChild[1] + 1]).addClass('smartSelect_active');
        }
    }

    this.whoIsThisChild = function (child) {
        var childrenQty = this.childrenDom.length;
        var i = 0;
        for (i; i < childrenQty; i++) {
            var currentChild = $(this.childrenDom[i]);
            if (currentChild.is(child))
                return [currentChild, i];
        }
        return [null, -1];
    }
    
    this.getDataFromActiveChild = function(activeChild){
        var output = {};
        var cbAttributesQty = this.callBackAttributes.length;
        var i = 0;
        for (i; i < cbAttributesQty; i++) {
            var nameOfAttribute = this.callBackAttributes[i].toString();
            var attrValue = $(activeChild[0]).data(nameOfAttribute);
            output[nameOfAttribute] = attrValue;
        }
        return output;
    }

    $(document).ready(function () {
        me.smartSelect = $((me.idMainContainer).toString());
        me.childrenDom = $(me.smartSelect.children('div')[0]).children();
        me.children = [];
        me.attributes = [];
        me.nameAttribute = "";
        me.inputDom = $(me.smartSelect.children('input')[0]);
        
        $(document).on('keydown', function (event) {
            if (me.inputDom.is(":focus")) {
                var activeChild = me.whoIsActive();
                switch (event.keyCode) {
                    case 38:
                        me.selectUpperChildren(activeChild);
                        break;
                    case 40:
                        me.selectLowerChildren(activeChild);
                        break;
                    case 13:
                        if (me.selectionFromListCallBackFunction === null)
                            console.log("callback function has not been set");
                        else
                            me.selectionFromListCallBackFunction(me.getDataFromActiveChild(activeChild));
                        break;
                    default:
                        break;
                }
            }
        });

        me.smartSelect.on('focusin', function (event) {
            event.stopPropagation();
            me.smartSelect.children('div').css('display', 'block');
        });

        me.smartSelect.on('focusout', function (event) {
            event.stopPropagation();
            setTimeout(function () { me.smartSelect.children('div').css('display', 'none'); }, 300);
        });

        me.smartSelect.on('click', '>>', function (event) {
            var activeChild = $(event.target);
            if (me.selectionFromListCallBackFunction === null)
                console.log("callback function has not been set");
            else
                me.selectionFromListCallBackFunction(me.getDataFromActiveChild(activeChild));
        });

        me.smartSelect.on('mouseenter', '>>', function (event) {
            var currentChild = $(event.target);
            currentChild.addClass('smartSelect_active');
            var activeChild = me.whoIsThisChild(currentChild);
            me.removeClassFromAllChildren(activeChild[1]);
        });

        me.smartSelect.on('mouseleave', '>>', function (event) {
            $(event.target).removeClass('smartSelect_active');
        });

        me.smartSelect.on('keyup', 'input', function (event) {
            if (event.keyCode === 38 || event.keyCode === 40 || event.keyCode === 13)
                return;
            
            if (me.inputOnChangeCallBackFunction === null) {
                console.log("on input change callback function has not been set");
                return;
            }

            me.inputOnChangeCallBackFunction($(event.target).val().toString());

        });

    });

}
