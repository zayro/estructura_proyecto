/*
###############################################
INICIA FILTROS
###############################################
*/

//  CREAR UNA AGRUPACION REPEAT
app.filter('agrupacion', ['$parse', function ($parse) {
return function (list, group_by) {

var filtered = [];
var prev_item = null;
var group_changed = false;
// this is a new field which is added to each item where we append "_CHANGED"
// to indicate a field change in the list
//was var new_field = group_by + '_CHANGED'; - JB 12/17/2013
var new_field = 'group_by_CHANGED';

// loop through each item in the list
angular.forEach(list, function (item) {

group_changed = false;

// if not the first item
if (prev_item !== null) {

// check if any of the group by field changed

//force group_by into Array
group_by = angular.isArray(group_by) ? group_by : [group_by]; 

//check each group by parameter
for (var i = 0, len = group_by.length; i < len; i++) {
if ($parse(group_by[i])(prev_item) !== $parse(group_by[i])(item)) {
group_changed = true;
}
}


}// otherwise we have the first item in the list which is new
else {
group_changed = true;
}

// if the group changed, then add a new field to the item
// to indicate this
if (group_changed) {
item[new_field] = true;
} else {
item[new_field] = false;
}

filtered.push(item);
prev_item = item;

});

return filtered;
};
}]);


/*
###############################################
INICIA FILTROS PARA VALOR UNICO EN UN REPEAT
###############################################
*/


app.filter('unique', function() {
return function(collection, keyname) {
var output = [], 
keys = [];

angular.forEach(collection, function(item) {
var key = item[keyname];
if(keys.indexOf(key) === -1) {
keys.push(key);
output.push(item);
}
});

return output;
};
});


/*
###############################################
INICIA FILTROS PARA CREAR SUMATORIAS REPEAT
###############################################
*/

app.filter('filtro_sumatoria', function () {

return function (data, key) {

if(angular.isUndefined(data)){ return 0; }else{


if (typeof (data) === 'undefined' && typeof (key)=== 'undefined') {

return 0;

}else{


var sum = 0;

for (var i = 0; i < data.length; i++) {

sum = (sum + parseInt(data[i][key]));

}

//debugger;

if (sum == 'undefined'){ return 0;}else{return sum;}

}
}
};

});

/*
###############################################
INICIA FILTROS PARA CREAR MULTIPLICAR REPEAT
###############################################
*/

app.filter('filtro_multiplicar', function () {


return function (data, key1, key2) {


if(angular.isUndefined(data)){ return 0; }else{



if (typeof (data) === 'undefined' && typeof (key1) === 'undefined' && typeof (key2) === 'undefined') {return 0;}else{

var sum = 0;

for (var i = 0; i < data.length; i++) {

sum = (sum + parseInt((data[i][key1] * data[i][key2])));

}

if (sum == 'undefined'){ return 0;}else{return sum;}
}

}
};

});



/*
###############################################
INICIA FILTRO SACAR UN VALOR DE UN DATA
###############################################
*/

app.filter('distinto', function () {


return function (data, key1) {


if(angular.isUndefined(data)){ return 0; }else{



if (typeof (data) === 'undefined' && typeof (key1) === 'undefined') {return 0;}else{

var sum = 0;

for (var i = 0; i < data.length; i++) {

sum = (sum + parseInt((data[i][key1] * data[i][key2])));

}


return data;

}

}
};

});
