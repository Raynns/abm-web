<div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="mb-5 flex items-center gap-2">
        <svg class="h-5 w-5 shrink-0 text-blue-700" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
             stroke-linejoin="round" aria-hidden="true">
            <path d="M12 21s7-5.2 7-12a7 7 0 1 0-14 0c0 6.8 7 12 7 12Z"/>
            <circle cx="12" cy="9" r="2.5"/>
        </svg>
        <h3 class="font-bold text-slate-900">Alamat Pengiriman</h3>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Provinsi</label>
            <select id="province_id" name="province_id"
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-100">
                <option value="">Pilih Provinsi</option>
                @foreach(\App\Models\Province::orderBy('name')->get() as $province)
                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Kabupaten/Kota</label>
            <select id="regency_id" name="regency_id" disabled
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-100">
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Kecamatan</label>
            <select id="district_id" name="district_id" disabled
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-100">
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Desa/Kelurahan</label>
            <select id="village_id" name="village_id" disabled
                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-100">
                <option value="">Pilih Desa/Kelurahan</option>
            </select>
        </div>
    </div>

    <div class="mt-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat Detail</label>
        <textarea name="address_detail" rows="3"
            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-700 focus:ring-2 focus:ring-blue-100"
            placeholder="Jalan, nomor rumah, RT/RW, patokan"></textarea>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const province = document.getElementById('province_id');
    const regency = document.getElementById('regency_id');
    const district = document.getElementById('district_id');
    const village = document.getElementById('village_id');

    function fill(select, items, placeholder) {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        items.forEach(item => {
            select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
        select.disabled = false;
    }

    province.addEventListener('change', async () => {
        regency.disabled = true;
        district.disabled = true;
        village.disabled = true;

        const res = await fetch(`/regions/regencies/${province.value}`);
        fill(regency, await res.json(), 'Pilih Kabupaten/Kota');
    });

    regency.addEventListener('change', async () => {
        district.disabled = true;
        village.disabled = true;

        const res = await fetch(`/regions/districts/${regency.value}`);
        fill(district, await res.json(), 'Pilih Kecamatan');
    });

    district.addEventListener('change', async () => {
        village.disabled = true;

        const res = await fetch(`/regions/villages/${district.value}`);
        fill(village, await res.json(), 'Pilih Desa/Kelurahan');
    });
});
</script>