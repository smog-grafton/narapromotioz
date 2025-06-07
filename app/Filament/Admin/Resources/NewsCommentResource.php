<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NewsCommentResource\Pages;
use App\Filament\Admin\Resources\NewsCommentResource\RelationManagers;
use App\Models\NewsComment;
use App\Models\NewsArticle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;

class NewsCommentResource extends Resource
{
    protected static ?string $model = NewsComment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'News Comments';
    
    protected static ?string $modelLabel = 'Comment';
    
    protected static ?string $pluralModelLabel = 'Comments';
    
    protected static ?string $navigationGroup = 'News Management';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Comment Details')
                    ->schema([
                        Select::make('news_id')
                            ->relationship('article', 'title')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->label('Article'),
                        Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Reply to Comment')
                            ->placeholder('Select if this is a reply'),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Name'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->label('Email'),
                        Forms\Components\TextInput::make('website')
                            ->url()
                            ->maxLength(255)
                            ->label('Website'),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Comment Content')
                    ->schema([
                        Forms\Components\RichEditor::make('comment')
                            ->required()
                            ->columnSpanFull()
                            ->label('Comment'),
                    ]),
                    
                Forms\Components\Section::make('Status')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('article.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->label('Article'),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent Comment')
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label('Email'),
                Tables\Columns\TextColumn::make('comment')
                    ->limit(50)
                    ->html()
                    ->label('Comment'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'spam' => 'danger',
                        'rejected' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->label('Posted'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('article')
                    ->relationship('article', 'title')
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('replies_only')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('parent_id'))
                    ->label('Replies Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (NewsComment $record) {
                        $record->update(['status' => 'approved']);
                    })
                    ->visible(fn (NewsComment $record): bool => $record->status !== 'approved'),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (NewsComment $record) {
                        $record->update(['status' => 'rejected']);
                    })
                    ->visible(fn (NewsComment $record): bool => $record->status !== 'rejected'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update([
                                    'status' => 'approved',
                                    'approved_at' => now(),
                                ]);
                            });
                        }),
                    Tables\Actions\BulkAction::make('reject')
                        ->label('Reject Selected')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['status' => 'rejected']);
                            });
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsComments::route('/'),
            'create' => Pages\CreateNewsComment::route('/create'),
            'edit' => Pages\EditNewsComment::route('/{record}/edit'),
        ];
    }
}
