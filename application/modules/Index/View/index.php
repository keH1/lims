<style>
    body {
        font-family: Verdana;
        font-size: 11px;
    }

    h2 {
        margin-bottom: 0;
    }

    small {
        display: block;
        margin-top: 40px;
        font-size: 9px;
    }

    small,
    small a {
        color: #666;
    }

    a {
        color: #000;
        text-decoration: underline;
        cursor: pointer;
    }

    #toolbar [data-wysihtml5-action] {
        float: right;
        margin-right: 10px;
    }

    #toolbar,
    textarea {
        width: 600px;
        padding: 5px;
    }

    textarea {
        height: 280px;
        border: 2px solid green;
        box-sizing: boder-box;
        -webkit-box-sizing: border-box;
        -ms-box-sizing: border-box;
        -moz-box-sizing: border-box;
        font-family: Verdana;
        font-size: 11px;
    }

    textarea:focus {
        color: black;
        border: 2px solid black;
    }

    .wysihtml5-command-active {
        font-weight: bold;
    }
</style>

<form>
    <div id="toolbar">
        <a data-wysihtml5-command="bold" title="CTRL+B">bold</a> |
        <a data-wysihtml5-command="italic" title="CTRL+I">italic</a>
        <a data-wysihtml5-action="change_view">switch to html view</a>
    </div>
    <textarea id="textarea" placeholder="Enter text ..."></textarea>
    <br><input type="reset" value="Reset form!">
</form>
