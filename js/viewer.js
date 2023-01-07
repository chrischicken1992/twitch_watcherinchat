$(function() {
  $("#tablesorter").tablesorter({
	widgets: ["filter", "cssStickyHeaders"],
	widthFixed : true,
	widgetOptions : {
      filter_external : '.search',
      filter_columnFilters: false,
      filter_reset: '.reset',
	  cssStickyHeaders_attachTo: '.wrapper', // or $('.wrapper')
    },
	//Sort by Name ASC
	sortList : [[1,0]]
	  
  });
  
  //Hide Users, if localStorage is set
  var storedNames = JSON.parse(localStorage.getItem("hideUsers"));
  if(storedNames != null) {
	  var strArray = storedNames.toString().split(",");
        
	for(var i = 0; i < strArray.length; i++){	
		$('#' + strArray[i]).remove();	
	}
	var t = $('#tablesorter');
	document.getElementById('auge').innerHTML = "👁️";
	t.trigger('update');
  }
  
  //Turn on Dark Mode, if Dark Mode is true in localStorage
  var readDarkMode = localStorage.getItem("darkMode");
  if(readDarkMode != null) {
	if(readDarkMode=="true") {
		document.getElementById('dark').disabled = false;
		document.getElementById('dark-mode-text').innerHTML = '☀️';
	} else {
		document.getElementById('dark').disabled = true;
		document.getElementById('dark-mode-text').innerHTML = '🌙';
	}
  }
});

//Remove rows
$('#tablesorter').delegate('button.remove', 'click' ,function() {
      var t = $('#tablesorter');
      $(this).closest('tr').remove();
	  var names = $(this).closest('tr').attr('id')
	  var storedNames = JSON.parse(localStorage.getItem("hideUsers"));
	  if(storedNames == null) {
		  storedNames = []
	  }
	  storedNames.push(names)
	  localStorage.setItem("hideUsers", JSON.stringify(storedNames));
	  document.getElementById('auge').innerHTML = "👁️";
      t.trigger('update');
      return false;
    });

//Toggle Darkmode
function changeDarkMode() {
	var darkswitch = document.getElementById('dark-mode-text').innerHTML;
	if(darkswitch=='🌙') {
		document.getElementById('dark').disabled = false;
		document.getElementById('dark-mode-text').innerHTML = '☀️';
		localStorage.setItem("darkMode", true);
	} else {
		document.getElementById('dark').disabled = true;
		document.getElementById('dark-mode-text').innerHTML = '🌙';
		localStorage.setItem("darkMode", false);
	}
}

// Autoscroller
i1 = -1;
var speed1 = 1;
var scroll_ok = true;
function scroll() 
{
	i1 = i1 + speed1;
	var div = document.getElementById("tabelle");
	var timeset = 40;
	div.scrollTop = i1;
	if (i1 == 0) { var timeset = 5000; }
	if (i1 > div.scrollHeight - 160) {i1 = -1;}
	if(scroll_ok == true) {
		t1=setTimeout("scroll()",timeset);
	}
}

//Toggle Autoscroller
function scroll_on_off() {
	if(scroll_ok == true) {
		scroll_ok = false;
	} else {
		scroll_ok = true;
		scroll();
	}
}

//Show all Users again and remove localStorage
function showAll() {
	localStorage.removeItem('hideUsers');
	document.location.reload(true);
}