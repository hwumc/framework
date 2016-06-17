{extends file="base.tpl"}
{block name="pagedescription"}{/block}
{block name="body"}
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="{$maxSize}"/>

        <fieldset>
            <legend>{message name="{$pageslug}-upload-header"}</legend>

            <div class="control-group">
                <label class="control-label" for="filename">{message name="{$pageslug}-upload-filename"}</label>
                <div class="controls">
                    <input type="text" id="filename" name="filename"
                           placeholder="{message name="{$pageslug}-upload-filename-placeholder"}" required="required"/>
                    <span class="help-inline">{message name="{$pageslug}-upload-filename-help"}</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="file">{message name="{$pageslug}-upload-file"}</label>
                <div class="controls">
                    <input type="file" id="file" name="file" required="required"/><span
                            class="help-inline">{message name="{$pageslug}-maxsize"} {$humanMaxSize}</span>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="copyright">{message name="{$pageslug}-upload-copyright"}</label>
                <div class="controls">
                    <input type="text" class="input-xxlarge" id="copyright" name="copyright"
                           placeholder="{message name="{$pageslug}-upload-copyright-placeholder"}" required="required"/>
                    <span class="help-inline">{message name="{$pageslug}-upload-copyright-help"}</span>
                </div>
            </div>
        </fieldset>

        <div class="control-group">
            <div class="controls">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">{message name="upload"}</button>
                    <a href="{$cScriptPath}/ManageGroups" class="btn">{message name="getmeoutofhere"}</a>
                </div>
            </div>
        </div>
    </form>
{/block}