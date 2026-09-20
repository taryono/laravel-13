 <div class="mb-3">
     <label>Tipe</label>
     <select name="type" class="form-select" id="type-select">
         <option value="image" @selected(old('type', $slider->type) === 'image')>Image</option>
         <option value="html" @selected(old('type', $slider->type) === 'html')>HTML / Blade</option>
     </select>
 </div>

 <div class="mb-3 type-image">
     <label>Gambar</label>
     <input type="file" name="image" class="form-control">
     @if ($slider->image_url)
         <img src="{{ $slider->image_url }}" class="mt-2" style="max-height:120px">
     @endif
     <input type="text" name="alt_text" placeholder="Alt text" class="form-control mt-2"
         value="{{ old('alt_text', $slider->alt_text) }}">
 </div>

 <div class="mb-3 type-html">
     <label>Blade View (nama file di resources/views/sliders/)</label>
     <input type="text" name="blade_view" class="form-control" value="{{ old('blade_view', $slider->blade_view) }}"
         placeholder="contoh: promo">

     <label class="mt-2">Atau HTML langsung</label>
     <textarea name="html_content" rows="6" class="form-control">{{ old('html_content', $slider->html_content) }}</textarea>
 </div>

 <div class="row">
     <div class="col-md-4 mb-3">
         <label>Link URL</label>
         <input type="text" name="link_url" class="form-control" value="{{ old('link_url', $slider->link_url) }}">
     </div>
     <div class="col-md-2 mb-3">
         <label>Target</label>
         <select name="link_target" class="form-select">
             <option value="_self" @selected($slider->link_target === '_self')>_self</option>
             <option value="_blank" @selected($slider->link_target === '_blank')>_blank</option>
         </select>
     </div>
     <div class="col-md-2 mb-3">
         <label>Order</label>
         <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order ?? 0) }}">
     </div>
     <div class="col-md-4 mb-3">
         <label>Jadwal (opsional)</label>
         <input type="datetime-local" name="start_at" class="form-control"
             value="{{ optional($slider->start_at)->format('Y-m-d\TH:i') }}">
         <input type="datetime-local" name="end_at" class="form-control mt-1"
             value="{{ optional($slider->end_at)->format('Y-m-d\TH:i') }}">
     </div>
 </div>

 <div class="form-check mb-3">
     <input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $slider->is_active ?? true))>
     <label class="form-check-label">Aktif</label>
 </div>
