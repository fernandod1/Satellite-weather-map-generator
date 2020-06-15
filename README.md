# Satellite weather map animated image generator
PHP script that scrapes satellite weather maps static images from some sources and generates an animated satellite weather map images.

You can configure/add your own satellite weather images sources. Actually script is configured to scrap images from these sources:

https://dsx.weather.com/util/image/map/DCT_SPECIAL11_1280x720.jpg<br>
http://rammb.cira.colostate.edu/ramsdis/online/images/latest/tropical/tropical_ge_4km_ir4_floater_2.gif<br>
https://weather.msfc.nasa.gov/cgi-bin/get-abi?satellite=GOESEastfullDiskband13&palette=ir2.pal&lat=16&lon=-62&type=Image&width=640&height=480&zoom=1&quality=50&map=standard<br>

--------------------
REQUIREMENTS:
--------------------
- PHP >=7.0<br>
- Librería imagick  >= 3.1.0RC1

--------------------------------------------------
CONFIGURE FRECUENCY OF ANIMATED IMAGES GENERATOR:
--------------------------------------------------
You must create some cronjobs in your servers so catch new static satellite wweather map images from sources ans generate animated images. Adjunst frecuency of cronjobs to your needs. Command to add is:

wget http://yourwebsite.com/script/index.php?op=cronjobTIPO1 >/dev/null 2>&1<br>
wget http://yourwebsite.com/script/index.php?op=cronjobTIPO2 >/dev/null 2>&1<br>
wget http://yourwebsite.com/script/index.php?op=cronjobTIPO3 >/dev/null 2>&1<br>

----------------------------------
DISPLAY ANIMATED GENERATED IMAGE:
----------------------------------
Just add a html code like:

img src=https://yourwebsite.com/script/animation1.gif
img src=https://yourwebsite.com/script/animation2.gif
img src=https://yourwebsite.com/script/animation3.gif

------------------------------------------------
EXAMPLES OF OUTPUT ANIMATED IMAGE GENERATED:
------------------------------------------------

<img src=animation2.gif>


