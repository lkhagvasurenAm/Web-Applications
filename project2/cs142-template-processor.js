'use strict';
function Cs142TemplateProcessor(template){
	   this.template = template;
}
Cs142TemplateProcessor.prototype.fillIn = function(dictionary) {
			var rs = this.template;
			var match = this.template.match(/{{[^{]*}}/g);
			var p, k, a;
			for (var i = 0; i < match.length; i++) {
				p = match[i];
				k = p.replace("{{", "");
				k = k.replace("}}", "");
				a = dictionary[k] || '';
				rs = rs.replace(p, a);
			}
	return rs;
	};