guard :concat, :type => "css", :files => %w[styles], :input_dir => "joejiko.com/assets/css", :output => "joejiko.com/assets/css/styles.min"

guard :concat, :type => "js", :files => %w[main], :input_dir => "joejiko.com/assets/js/app", :output => "joejiko.com/assets/js/app/scripts.min"

module ::Guard
  class Refresher < Guard
    def run_all
      # refresh
    end

    def run_on_additions(paths)
      refresh
    end

    def run_on_removals(paths)
      refresh
    end

    def refresh
      `php artisan guard:refresh`
    end
  end
end

require 'cssmin'
require 'jsmin'

guard :refresher do
  watch(%r[joejiko.com/assets/js/app/.+])
  watch(%r[joejiko.com/assets/css/.+])
  watch(%r{app/config/packages/way/guard-laravel/guard.php}) do |m|
    `php artisan guard:refresh`
  end
  watch('joejiko.com/assets/css/styles.min.css') do |m|
    css = File.read(m[0])
    File.open(m[0], 'w') { |file| file.write(CSSMin.minify(css)) }
  end
  watch('joejiko.com/assets/js/app/scripts.min.js') do |m|
    js = File.read(m[0])
    File.open(m[0], 'w') { |file| file.write(JSMin.minify(js)) }
  end
end

guard :sass, :input => 'app/assets/sass', :output => 'joejiko.com/assets/css', :compass => true