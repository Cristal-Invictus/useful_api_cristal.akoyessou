<?php

namespace App\Http\Controllers;
use App\Models\ShortLink;
use App\Support\CodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class ShortLinksController extends Controller
{
    //
        // POST /shorten
    public function store(Request $request)
    {
        $data = $request->validate([
            'original_url' => ['required','url','starts_with:http://,https://'],
            'custom_code'  => [
                'nullable','string','max:10',
                'regex:/^[A-Za-z0-9_-]{1,10}$/',
                Rule::unique('short_links','code'),
            ],
        ]);

        $user = $request->user();
        $code = $data['custom_code'] ?? $this->uniqueCode();

        $link = ShortLink::create([
            'user_id'      => $user->id,
            'original_url' => $data['original_url'],
            'code'         => $code,
            'clicks'       => 0,
        ]);

        return response()->json([
            'id'         => $link->id,
            'user_id'    => $link->user_id,
            'original_url'=> $link->original_url,
            'code'       => $link->code,
            'clicks'     => $link->clicks,
            'created_at' => $link->created_at->toISOString(),
        ], 201);
    }

    // GET /links
    public function index(Request $request)
    {
        $links = $request->user()->shortLinks()
            ->select('id','original_url','code','clicks','created_at')
            ->latest('id')
            ->get();

        return response()->json($links);
    }

    // DELETE /links/{id}
    public function destroy(Request $request, int $id)
    {
        $link = ShortLink::find($id);
        if (! $link) {
            return response()->json(['message' => 'Link not found'], 404);
        }
        if ($link->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $link->delete();
        return response()->json(['message' => 'Link deleted successfully']);
    }

    // GET /s/{code} — public, +1 clic
    public function redirect(string $code)
    {
        $link = ShortLink::where('code', $code)->first();
        if (! $link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        $isActive = DB::table('user_modules')
            ->where('user_id', $link->user_id)
            ->where('module_id', 1)
            ->where('active', true)
            ->exists();

        if (! $isActive) {
            return response()->json([
                'error' => 'Module inactive. Please activate this module to use it.'
            ], 403);
        }

        ShortLink::where('id', $link->id)->update([
            'clicks' => DB::raw('clicks + 1')
        ]);

        return redirect()->away($link->original_url, 302);
    }

    protected function uniqueCode(): string
    {
        do {
            $code = CodeGenerator::make(6);
        } while (ShortLink::where('code', $code)->exists());
        return $code;
    }

}

