from typing import Any
import asyncio
from telegram import Update
from telegram.ext import ApplicationBuilder, CommandHandler, MessageHandler, ContextTypes, filters
import threading
import sqlite3
import random
import datetime
import prettytable as pt
from telegram.constants import ParseMode

class Quiz:
    context: ContextTypes.DEFAULT_TYPE
    timerOfRe = 10
    times = 3
    score = 0
    tur = False
    qs = ''
    aw = ''
    ignorTimes = 0

    def __init__(self, chat_id, context, nb: bool, score=0):
        self.chat_id = chat_id
        self.context = context
        self.enable = nb
        self.score = score


quiz_coll: list[Any] = []


def convert_arabic_to_english(text):
    arabic_digits = '٠١٢٣٤٥٦٧٨٩'
    english_digits = '0123456789'
    translation_table = str.maketrans(arabic_digits, english_digits)
    return text.translate(translation_table)


async def help_func(update: Update, context: ContextTypes.DEFAULT_TYPE):
    await context.bot.sendMessage(chat_id=update.effective_chat.id,
                                  text=f"Hallo {update.message.from_user.username} \n "
                                       f"To Start Quiz, send /start\n "
                                       f"To Stop Quiz send /stop")
async def get_users_func(update: Update, context: ContextTypes.DEFAULT_TYPE):
    userRank = rank(update)
    if userRank =='owner' or userRank=='admin':
        db = sqlite3.connect('qus.db')
        cr = db.cursor()
        cr.execute(f'select username,score from quiz_data')
        users = cr.fetchall()

        table = pt.PrettyTable(['Username', 'Score'])
        table.align['Username'] = 'l'
        table.align['Score'] = 'r'

        for username, score in users:
            table.add_row([f'{username}', f'{score}'])
        await context.bot.sendMessage(chat_id=update.effective_chat.id, text=f"<pre>{table}</pre>",
                                      parse_mode=ParseMode.HTML)
    else:

        await context.bot.sendMessage(chat_id=update.effective_chat.id,text=f"You do not have permissions !")
def rank(update:Update) -> str:
    db = sqlite3.connect('qus.db')
    cr = db.cursor()
    cr.execute(f'select rank from quiz_data WHERE user_id={update.effective_chat.id}')
    return cr.fetchall()[0][0]


async def start_func(update: Update, context: ContextTypes.DEFAULT_TYPE):
    for quiz in quiz_coll:
        if quiz.chat_id == update.effective_chat.id:
            quiz.enable = True
            quiz.ignorTimes = 0
            await context.bot.sendMessage(chat_id=update.effective_chat.id, text="َQuiz has been reactive...😃")
            return
    db = sqlite3.connect('qus.db')
    cr = db.cursor()
    cr.execute(f'select user_id from quiz_data WHERE user_id={update.effective_chat.id}')

    # cr.execute(f"DELETE FROM qus WHERE aw LIKE '% %'")

    rs = cr.fetchone()
    score = 0
    if not rs:
        cr.execute(
            f'INSERT INTO quiz_data ("username","user_id","score","join_date","status") VALUES ("{update.message.from_user.username}","{update.effective_chat.id}","0","{datetime.datetime.now().strftime("%Y.%m.%d %H:%M:%S")}","active");')
        db.commit()

    else:
        cr.execute(f'select score from quiz_data WHERE user_id={update.effective_chat.id}')
        score = int(cr.fetchone()[0])
    db.close()

    quiz_coll.append(Quiz(update.effective_chat.id, context, True, score))
    await context.bot.sendMessage(chat_id=update.effective_chat.id, text="َQuiz has been Started...")


async def stop_func(update: Update, context: ContextTypes.DEFAULT_TYPE):
    for key, quiz in enumerate(quiz_coll):
        if quiz.chat_id == update.effective_chat.id:
            quiz_clone = quiz_coll.copy()
            quiz_clone.pop(key)
            quiz_coll.clear()
            for q in quiz_clone:
                quiz_coll.append(q)
            loop = asyncio.get_event_loop()
            asyncio.run_coroutine_threadsafe(send_new_message(quiz, "َQuiz has been Stoped..."), loop)
            return

    await context.bot.sendMessage(chat_id=update.effective_chat.id, text="َQuiz is already Stoped...")


def get_qs() -> list:
    n = random.randint(1, 12205)
    db = sqlite3.connect('qus.db')
    cr = db.cursor()
    cr.execute(f'SELECT * FROM qus WHERE id =  {n}')
    _, qs, aw = cr.fetchone()
    db.close()
    return [qs, aw]

def first2char(s):
    return s[:2]
async def msg_func(update: Update, context: ContextTypes.DEFAULT_TYPE):
    for quiz in quiz_coll:
        if quiz.chat_id == update.effective_chat.id:
            quiz.ignorTimes = 0
            if quiz.enable:
                if update.message.text=='pass':
                    db = sqlite3.connect('qus.db')
                    cr = db.cursor()
                    cr.execute(f'SELECT rank FROM quiz_data WHERE user_id = {update.effective_chat.id}')
                    if cr.fetchone()[0]=='owner':
                        quiz.times = 3
                        quiz.ignorTimes += 1
                        quiz.tur = False
                        quiz.qs = None
                        if quiz.ignorTimes == 10: quiz.enable = False
                        loop5 = asyncio.get_event_loop()
                        asyncio.run_coroutine_threadsafe(qus_send(quiz), loop5)
                if filter_text(quiz.aw) == filter_text(convert_arabic_to_english(update.message.text)) and quiz.tur:
                    quiz.score += 1
                    db = sqlite3.connect('qus.db')
                    cr = db.cursor()
                    cr.execute(f"UPDATE quiz_data SET score ='{quiz.score}' WHERE user_id ='{quiz.chat_id}'")
                    db.commit()
                    db.close()
                    loop = asyncio.get_event_loop()
                    asyncio.run_coroutine_threadsafe(send_new_message(quiz, f"Gut gemacht {update.message.from_user.username} 👍 -> Score: {quiz.score}"),
                                                     loop)
                    quiz.times = 3
                    quiz.timerOfRe = 10
                    quiz.qs, quiz.aw = get_qs()

            return
    await context.bot.sendMessage(chat_id=update.effective_chat.id,
                                  text=f"Hallo {update.message.from_user.username} ! Please send /help to show info")


async def send_new_message(qu: Quiz, msg: str):
    await qu.context.bot.send_message(chat_id=qu.chat_id, text=msg)


def filter_text(txt: str) -> str:
    try:

        myText1 = txt.replace("أ", "ا").replace("آ", "ا").replace("إ", "ا").replace("ة", "ه").replace("ؤ", "و")
        while True:
            myText1 = myText1.replace("وو", "و")
            myText1 = myText1.replace("اا", "ا")
            myText1 = myText1.replace("رر", "ر")
            myText1 = myText1.replace("زز", "ز")
            myText1 = myText1.replace("ــ", "ـ")
            myText1 = myText1.replace("سس", "س")
            myText1 = myText1.replace("كك", "ك")
            myText1 = myText1.replace("عع", "ع")
            myText1 = myText1.replace("هه", "ه")
            myText1 = myText1.replace("يي", "ي")

            myText1 = myText1.replace("بب", "ب")
            myText1 = myText1.replace("نن", "ن")
            myText1 = myText1.replace("طط", "ط")
            myText1 = myText1.replace("صص", "ص")
            myText1 = myText1.replace("ثث", "ث")
            myText1 = myText1.replace("قق", "ق")
            myText1 = myText1.replace("فف", "ف")
            myText1 = myText1.replace("غغ", "غ")
            myText1 = myText1.replace("خخ", "خ")
            myText1 = myText1.replace("حح", "ح")
            myText1 = myText1.replace("جج", "ج")
            myText1 = myText1.replace("دد", "د")
            myText1 = myText1.replace("تت", "ت")
            myText1 = myText1.replace("ئئ", "ئ")
            myText1 = myText1.replace("ظظ", "ظ")
            myText1 = myText1.replace("ضض", "ض")

            if not (("وو" in myText1) or ("اا" in myText1) or ("رر" in myText1) or ("زز" in myText1) or
                    ("ــ" in myText1) or ("كك" in myText1) or ("سس" in myText1) or ("عع" in myText1) or
                    ("بب" in myText1) or ("نن" in myText1) or ("طط" in myText1) or ("صص" in myText1) or
                    ("ثث" in myText1) or ("قق" in myText1) or ("فف" in myText1) or ("غغ" in myText1) or
                    ("خخ" in myText1) or ("حح" in myText1) or ("دد" in myText1) or ("تت" in myText1) or
                    ("ئئ" in myText1) or ("ظظ" in myText1) or ("ضض" in myText1) or
                    ("هه" in myText1) or ("يي" in myText1)):
                break
        if first2char(myText1) == "ال":
            myText1 = myText1[2:]
        return myText1
    except NameError:
        return txt


async def quiz_funk():
    try:
        while True:
            if len(quiz_coll) != 0:
                for quiz in quiz_coll:
                    if quiz.enable:
                        if quiz.timerOfRe == 10:
                            quiz.timerOfRe -= 1
                            if quiz.times == 3:
                                quiz.qs, quiz.aw = get_qs()
                                quiz.tur = True
                                quiz.times -= 1
                                loop1 = asyncio.get_event_loop()
                                asyncio.run_coroutine_threadsafe(qus_send(quiz), loop1)


                            elif quiz.times == 2:
                                quiz.times -= 1
                                loop2 = asyncio.get_event_loop()
                                asyncio.run_coroutine_threadsafe(qus_send(quiz), loop2)


                            elif quiz.times == 1:
                                quiz.times -= 1
                                loop3 = asyncio.get_event_loop()
                                asyncio.run_coroutine_threadsafe(qus_send(quiz), loop3)


                            elif quiz.times == 0:
                                quiz.times = 3
                                quiz.ignorTimes += 1
                                quiz.tur = False
                                quiz.qs = None
                                if quiz.ignorTimes == 10: quiz.enable = False
                                loop4 = asyncio.get_event_loop()
                                asyncio.run_coroutine_threadsafe(qus_send(quiz), loop4)

                        else:
                            quiz.timerOfRe -= 1
                            if quiz.timerOfRe == 0:
                                quiz.timerOfRe = 10
            await asyncio.sleep(1)
    except NameError:
        pass



async def qus_send(qu: Quiz):

    if qu.qs:
        await qu.context.bot.send_message(chat_id=qu.chat_id, text=qu.qs)
    else:
        if qu.enable:
            await qu.context.bot.send_message(chat_id=qu.chat_id, text= f' الاجابة الصحيحة هي: {qu.aw}')
        else:
            await qu.context.bot.send_message(chat_id=qu.chat_id, text=f'تم ايقاف المسابقة ...☹️ بسبب خمول اللاعب, للبدء /start')



async def qus_send2(qu: Quiz):
    await qu.context.bot.send_message(chat_id=qu.chat_id, text=qu.qs)


def main():
    application = ApplicationBuilder().token("8357218914:AAFhG8hF5ZMK3OknROHcNpnkyeuyYrjFmoo").build()
    # application = ApplicationBuilder().token("7763059278:AAGOq5p41F62XU0DTwoeNKa4HBtHEDRr8j4").build()
    help_handler = CommandHandler('help', help_func)
    start_handler = CommandHandler('start', start_func)
    stop_handler = CommandHandler('stop', stop_func)
    get_users_handler = CommandHandler('users', get_users_func)
    message_handler = MessageHandler(filters.TEXT, msg_func)

    application.add_handler(help_handler)
    application.add_handler(start_handler)
    application.add_handler(stop_handler)
    application.add_handler(get_users_handler)
    application.add_handler(message_handler)
    print("Your Bot Is Started ...")
    application.run_polling()


_thread = threading.Thread(target=asyncio.run, args=(quiz_funk(),))
_thread.start()

main()
