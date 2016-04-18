Mugeda.script.push(function (mugeda) {
mugeda = Mugeda.getMugedaObject();
    mugeda.addEventListener("renderReady",function()
    {
        scene = mugeda.scene;

        var isInitShake = false;
        var mode = false;
        //var failCount = 0;
        var fid = 0;
        var shakeAcceleration = 8;
        var showLotAcceleration = 20;
        var shakeAccelerationIncludingGravity = 12;
        var showLotAccelerationIncludingGravity = 30;
       // var help = scene.getObjectByName("help");
        
        function initShake() 
        {
            isInitShake = true;
            if (window.DeviceMotionEvent) 
            {
                window.addEventListener('devicemotion', deviceMotionHandler, false);
            }
            else
            {
               // help.text = "点击按钮，摇出你的签";
            }
        }
        function deviceMotionHandler(event) 
        {
            var x = 0, y = 0, z = 0;
            if (event.acceleration === null || event.acceleration.x === undefined || event.acceleration.y === undefined || event.acceleration.z === undefined || event.acceleration.x === null || event.acceleration.y === null || event.acceleration.z === null) 
            {
                if(event.accelerationIncludingGravity)
                {
                    x = event.accelerationIncludingGravity.x;
                    y = event.accelerationIncludingGravity.y;
                    z = event.accelerationIncludingGravity.z;

                    var acceleration = Math.sqrt(x*x + y*y + z*z);
                    if(acceleration >= shakeAccelerationIncludingGravity)
                    {
                        scene.play();
                    }
                }
                else
                {
                   /* if(mode === false)
                    {
                        failCount++;
                        if(failCount >= 3)
                        {
                            mode = true;
                            help.text = "点击按钮，摇出你的签";
                        }
                    }*/
                }
            }
            else
            {
                x = event.acceleration.x;
                y = event.acceleration.y;
                z = event.acceleration.z;
                //help.text = "晃动手机，摇出你的签";
                var acceleration = Math.sqrt(x*x + y*y + z*z);
                if( acceleration >= shakeAcceleration)
                {
                    scene.play();
                }
            }
        }
        
        scene.addEventListener("enterframe",function()
        {
            if(!isInitShake)
            {
                initShake();
            }
        });
        
    });
});