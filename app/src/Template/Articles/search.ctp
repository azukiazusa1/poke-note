<div class="container" id="app">
    <div class="row">
        <div class="col m10">
            <?php for($i = 0; $i < 1000; $i++): ?>
                <div><?= $i ?></div>
            <?php endfor ?>
        </div>
        <div class="col m2">
            <div class="pinned">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input id="icon_prefix" type="text" class="validate">
                    </div>
                </div>
                <div class="row">
                <div class="input-field col s12">
                    <select>
                        <option value="" disabled selected>Choose your option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                    </select>
                    <label>並び順</label>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>