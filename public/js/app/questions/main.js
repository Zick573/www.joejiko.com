define(["require","jquery", "require/domReady!"], function(require, $) {
  console.log("@questions loaded");

  return {
    start: function(){
      require(
        [
          "questions/ask"
        ],
        function(Ask){
          Ask.start();
      });
    }
  };
});