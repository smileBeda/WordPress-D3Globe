var siteURL = php_api_object.siteURL
var pageSlug = php_api_object.page_slug
// // ms to wait after dragging before auto-rotating
var rotationDelay = 2000
// scale of the globe (not the canvas element)
var scaleFactor = 0.6
var w = document.getElementById('globe-container').offsetWidth - (document.getElementById('globe-container').offsetWidth / 100 *10)
var h = document.getElementById('globe-container').offsetHeight - (document.getElementById('globe-container').offsetWidth / 100 *10)
var scl = Math.min(w, h)/2.8; 
// autorotation speed
var degPerSec = 12
// start angles
var angles = { x: -20, y: 40, z: 0}

var colorWater = 'rgba(31, 58, 147, 0.8)' //use php_api_object.VAR
var colorLandBorder = 'black' //use php_api_object.VAR
var colorLand = 'rgba(235, 151, 78, 0.8)' //use php_api_object.VAR
var colorGraticule = 'transparent' //use php_api_object.VAR
var colorCountry = 'rgba(30, 130, 76, 1.0)' //use php_api_object.VAR
var colorCountryNotVisited = 'white' //use php_api_object.VAR
var colorCountryVisited = 'rgba(30, 130, 76, 0.8)' //use php_api_object.VAR
var link = ''
var currentpostfield = php_api_object.countries_visited
function mouseOver() {
  stopRotation()
}

function mouseOut() {
    startRotation(rotationDelay)
}

function onCountryClick(){

  if (currentCountry) {
    var country = countryList.find(function(c) {
      return c.id === currentCountry.id
    })
  }

  if (currentCountry && currentpostfield.includes(country.id) == true) {
    var link = siteURL + "/" + pageSlug + "/" + (country && country.name)
	var modalId = "#country_info_of_" + (country && country.id)
  	//if chosen to open link then window.open(link), otherwise
  	jQuery(modalId).modal()//Insert a View with modals ID'd to country_info_of_[types field='country-json-id' output='raw'][/types]
  }
}


function enter(country) {
  mouseOver()
}

function leave(country) {
  mouseOut()
}

//
// Variables
//

var current = d3.select('#current')
var canvas = d3.select('#globe-container')
var context = canvas.node().getContext('2d')
var water = {type: 'Sphere'}
var projection = d3.geoOrthographic().precision(0.1)
var graticule = d3.geoGraticule10()
var path = d3.geoPath(projection).context(context)
var v0 // Mouse position in Cartesian coordinates at start of drag gesture.
var r0 // Projection rotation as Euler angles at start.
var q0 // Projection rotation as versor at start.
var lastTime = d3.now()
var degPerMs = degPerSec / 1000

var land, countries
var countryList
var autorotate, now, diff, roation
var currentCountry


//
// Functions
//

function setAngles() {
  var rotation = projection.rotate()
  rotation[0] = angles.y
  rotation[1] = angles.x
  rotation[2] = angles.z
  projection.rotate(rotation)
}

function scale() {
  width = w
  height = h
  canvas.attr('width', w).attr('height', h)
  projection
    .scale((scaleFactor * Math.min(w, h)) / 2)
    .translate([w / 2, h / 2])
  render()
}

function startRotation(delay) {
  autorotate.restart(rotate, delay || 0)
}

function stopRotation() {
  autorotate.stop()
}

function dragstarted() {
  v0 = versor.cartesian(projection.invert(d3.mouse(this)))
  r0 = projection.rotate()
  q0 = versor(r0)
  stopRotation()
}

function dragged() {
  var v1 = versor.cartesian(projection.rotate(r0).invert(d3.mouse(this)))
  var q1 = versor.multiply(q0, versor.delta(v0, v1))
  var r1 = versor.rotation(q1)
  projection.rotate(r1)
  render()
}

function dragended() {
  startRotation(rotationDelay)
}

function render() {

  context.clearRect(0, 0, w, h)

  fill(water, colorWater)
  //stroke(graticule, colorGraticule)
  stroke(countries, colorLandBorder)

  var filteredFeatures = countries.features.filter(function(feature) {
	return currentpostfield.includes(feature.id)
  })

  var nonfilteredFeatures = countries.features.filter(function(feature) {
	return !currentpostfield.includes(feature.id)
  })

  filteredFeatures.forEach(function(entry) {
    fill(entry, colorCountryVisited)
  });

  nonfilteredFeatures.forEach(function(entry) {
    fill(entry, colorLand)
  });

  if (currentCountry) {
	if(currentpostfield.includes(currentCountry.id)== true){
  	  fill(currentCountry, colorCountry)
	}
  }
}

function fill(obj, color) {
  context.beginPath()
  path(obj)
  context.fillStyle = color
  context.fill()
}

function stroke(obj, color) {
  context.beginPath()
  path(obj)
  context.strokeStyle = color
  context.stroke()
}

function rotate(elapsed) {
  now = d3.now()
  diff = now - lastTime
  if (diff < elapsed) {
    rotation = projection.rotate()
    rotation[0] += diff * degPerMs
    projection.rotate(rotation)
    render()
  }
  lastTime = now
}

function loadData(cb) {
  d3.json( php_api_object.siteURL += '/wp-content/plugins/D3-Globe-Rendering-for-WordPress/110m.json', function(error, world) {
    if (error) throw error
    d3.tsv('https://gist.githubusercontent.com/mbostock/4090846/raw/07e73f3c2d21558489604a0bc434b3a5cf41a867/world-country-names.tsv', function(error, countries) {
      if (error) throw error
      cb(world, countries)
    })
  })
}

function polygonContains(polygon, point) {
  var n = polygon.length
  var p = polygon[n - 1]
  var x = point[0], y = point[1]
  var x0 = p[0], y0 = p[1]
  var x1, y1
  var inside = false
  for (var i = 0; i < n; ++i) {
    p = polygon[i], x1 = p[0], y1 = p[1]
    if (((y1 > y) !== (y0 > y)) && (x < (x0 - x1) * (y - y1) / (y0 - y1) + x1)) inside = !inside
    x0 = x1, y0 = y1
  }
  return inside
}
                                       
function mousemove() {
  var c = getCountry(this)
  if (!c) {
    if (currentCountry) {
      leave(currentCountry)
      currentCountry = undefined
      render()
    }
    return
  }
  if (c === currentCountry) {
    return
  }
  currentCountry = c
  render()
  enter(c)
}

function getCountry(event) {
  var pos = projection.invert(d3.mouse(event))
  return countries.features.find(function(f) {
    return f.geometry.coordinates.find(function(c1) {
      return polygonContains(c1, pos) || c1.find(function(c2) {
        return polygonContains(c2, pos)
      })
    })
  })
}

setAngles()
                                           
function zoomed() {
	projection.scale(d3.event.transform.translate(projection).k * scl)
}
                                           
canvas
  .call(d3.drag()
    .on('start', dragstarted)
    .on('drag', dragged)
    .on('end', dragended)
   )
  .on('mousemove', mousemove)
  .on('click', onCountryClick)
  .call(d3.zoom()
	.scaleExtent([0.75, 50]) //bound zoom
	.on("zoom", zoomed)
  )
      
loadData(function(world, cList) {
  land = topojson.feature(world, world.objects.land)
  countries = topojson.feature(world, world.objects.countries)
  countryList = cList
  
  window.addEventListener('resize', scale)
  scale()
 
  autorotate = d3.timer(rotate)
})
