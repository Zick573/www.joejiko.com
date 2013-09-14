<hgroup class="parent">
  <h3>Project information</h3>
</hgroup>
<div class="children">
<hgroup>
  <h3 class="grid-5">Project Scope</h3>
  <h4 class="grid-7">What sort of project do you have in mind?</h4>
</hgroup>
<div class="scope">
<h5 style="text-align: center;">&mdash; Select all that apply &mdash;</h5>
<button class="g-button g-btn-yellow">Artist website</button>
<button class="g-button g-btn-yellow">Business website</button>
<button class="g-button g-btn-yellow">Personal website</button>
<button class="g-button g-btn-yellow">Blog design</button>
<button class="g-button g-btn-yellow">Graphic design</button>
<button class="g-button g-btn-yellow">Illustration</button>
<button class="g-button g-btn-yellow">Other</button>
<input type="text" placeholder="please explain" style="display: none;">
<input type="hidden" name="project-type" id="project-type" />
</div>

<div id="editable2" class="project-description message-body" contenteditable="true">
  <strong>Briefly describe your project...</strong>
</div>
<div class="grids widget-wrap">
  <div class="grid-5">
  	<h4>Deadline</h4>
    <h5>When do you want it done?</h5>
    <div data-dojo-type="dijit/Calendar"></div>
    <div style="display: none;">
      <input id="deadline" class="datepicker" name="deadline" type="date" data-dojo-type="dijit/form/DateTextBox" required="true" />
    </div>
  </div>
  <div class="grid-7">
  	<h4>Budget</h4>
    <h5>How much is this worth to you?</h5>
    <div id="budgetSlider" 
        name="horizontalSlider"
        data-dojo-type="dijit/form/HorizontalSlider"
        data-dojo-props="value:6,
        minimum: -10,
        maximum:10,
        discreteValues:11,
        intermediateChanges:true,
        showButtons:false">
        <ol data-dojo-type="dijit/form/HorizontalRuleLabels" container="topDecoration"
            style="height:1.5em;font-size:75%;color:gray;">
            <li>none</li>
            <li>low</li>
            <li>medium</li>
            <li>high</li>
            <li>priority</li>
            <li>whatever</li>
        </ol>
        <div data-dojo-type="dijit/form/HorizontalRule" container="bottomDecoration"
            count=11 style="height:5px;"></div>
        <ol data-dojo-type="dijit/form/HorizontalRuleLabels" container="bottomDecoration"
            style="height:1em;font-size:75%;color:gray;">
            <li>0</li>
            <li>$500</li>
            <li>$1000</li>
            <li>$1500</li>
            <li>$2500</li>
            <li>$5000+</li>
        </ol>
    </div>
    
  	<h4>How did you find me?</h4>
    <textarea id="referral"></textarea>
  </div>
</div>

<input id="budget" name="budget" type="hidden" />

<section id="website-design-info">
  {include file='./project/website-design.tpl'}
</section>
      
<section id="graphic-design-info">      
  {include file='./project/graphic-design.tpl'}
</section>

</div>