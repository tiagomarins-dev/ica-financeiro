// JavaScript Document

var $j = jQuery.noConflict();

$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtCliente" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search.php?p=cliente", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtCirurgia" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search.php?p=cirurgia", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtmodelo" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=modelo", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtplaca" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=placamae", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtprocessador" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=processador", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtmemoria" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=memoria", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})


$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txthd" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=hd", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtdrive" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=drive", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})



$j(function() {
function split( val ) {
return val.split( /,\s*/ );
}
function extractLast( term ) {
return split( term ).pop();
}
$j( "#txtso" )
.bind( "keydown", function( event ) {
if ( event.keyCode === $j.ui.keyCode.TAB &&
$j( this ).data( "autocomplete" ).menu.active ) {
event.preventDefault();
}
})
.autocomplete({
source: function( request, response ) {
$j.getJSON( "search_patrimonio.php?p=sistema", {
term: extractLast( request.term )
}, response );
},
search: function() {
var term = extractLast( this.value );
if ( term.length < 1 ) {
return false;
}
},
focus: function() {
return false;
},
select: function( event, ui ) {
var terms = split( this.value );
terms.pop();
//terms.push( ui.item.id );
terms.push(ui.item.label);
this.value = terms.join( "" );
return false;
}
})
.data( "autocomplete" )._renderItem = function( ul, item ) {
return $j( "<li style=\"text-align: left; font-size:10px;\"></li>" )
.data( "item.autocomplete", item )
.append( "<a><strong>" + item.label + "</strong></a>"  )
.appendTo( ul );
};
})
