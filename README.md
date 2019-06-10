# D3-Globe-Rendering-for-WordPress
A WordPress plugin rendering with one ShortCode a Spinning, Draggable, Pannable, Zoomable, Clickable D3.js Globe - with country borders and more.

Invoke the globe anywhere in Text Editor with `[D3-Globe]` ShortCode.
Invoke a single Country or a World Map in SVG Format (Not 3d) with the ShortCode `[SVG-Single-Country]`.
It supportes these attributes at the moment
```
'country' => '',//Country code
'active_areas' => '',/Area Code commaseparated
'active_areas_css' => '',//Valid CSS fill:red;
'active_areas_hover_css' => '',//Valid CSS fill:red;
```
The plugin incorporates mapes for southeastasia (Thailand, Cambodia, Vietnam) and a few others at the moment, country code can be found [here](https://gist.githubusercontent.com/mbostock/4090846/raw/07e73f3c2d21558489604a0bc434b3a5cf41a867/world-country-names.tsv). 
Areacodes at themoent must be retrieved from the console.
For more SVG maps I currently use [this source](https://mapsvg.com/)

Freely interpreted from [Planteray JS](http://planetaryjs.com) and [D3.js](https://d3js.org) based tools.

Can be activated like any other WordPress Plugin saving the hassle of custom coding such templates.

See a Demo [here](https://www.tukutoi.com/globe-maps/).

### NOTE 
When downloading master zip from GIT you need to unzip that and rename it (remove the master-appendix). Then, rezip the folder and upload that as the plugin.
